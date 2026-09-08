<?php

namespace App\Http\Controllers\Pages;

use App\Exports\TransferExport;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use App\Models\Transfer;
use App\Models\User;
use App\Services\CioFinanceService;
use App\Services\MekariQontakService;
use App\Services\XenditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $roles = Auth::user()->getRoleNames();

        $transfers = Transfer::with(['admin', 'investor.investor'])
                                ->when($roles->contains('Investor'), function ($query) {
                                    $query->where('investors_id', Auth::user()->id);
                                })
                                ->when($search, function ($query) use ($search) {
                                    $query->whereHas('investor', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%")
                                          ->orWhere('phone', 'like', "%$search%");
                                    })
                                    ->orWhereHas('admin', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%");
                                    })
                                    ->orWhere('code', 'like', "%$search%")
                                    ->orWhere('amount', 'like', "%$search%") 
                                    ->orWhere('status', 'like', "%$search%")
                                    ->orWhere('payment_method', 'like', "%$search%");
                                })
                                ->orderBy('id', 'DESC')
                                ->paginate($sort);

        return view("pages.transfer.index", compact("transfers"));
    }

    /**
     * Show the form for creating a new resource (3-Step Wizard).
     */
    public function create(CioFinanceService $financeService, XenditService $xenditService)
    {
        $investors = User::role('Investor')->with(['investors', 'investor'])->orderBy('name', 'ASC')->get();
        $bankGroups = $xenditService->getAvailableBanksGrouped();
        $settings = Setting::first();
        $financeBalance = $financeService->getBalance(true);

        return view("pages.transfer.create", compact("investors", "bankGroups", "settings", "financeBalance"));
    }

    /**
     * Store a newly created resource in storage with Finance CIO & Xendit integration.
     */
    public function store(
        Request $request, 
        CioFinanceService $financeService, 
        XenditService $xenditService, 
        MekariQontakService $qontakService
    ) {
        $request->validate([
            "balance_type"   => "required|in:manual,xendit",
            "investors_id"   => "required|exists:users,id",
            "amount"         => "required",
            "payment_method" => "required",
            "transfer_date"  => "required|date",
        ]);

        $balanceType = strtolower($request->balance_type);
        $investor = User::with(['investors', 'investor'])->findOrFail($request->investors_id);
        $settings = Setting::first();

        // Nominal gross dan potongan biaya admin otomatis dari pengaturan sistem
        $grossAmount = (float) str_replace('.', '', $request->amount);
        $adminFee    = (float) ($settings->admin_fee ?? 0);
        $netAmount   = max(0, $grossAmount - $adminFee);

        if ($grossAmount <= 0) {
            return back()->withErrors(['amount' => 'Nominal transfer harus lebih besar dari Rp 0.'])->withInput();
        }

        // 1. Verifikasi Status Channel & Sisa Saldo Real-Time
        $balanceInfo = $financeService->getBalance(true);
        $balanceData = $balanceInfo['data'] ?? [];
        $channelStatus = $balanceData['channel_status'] ?? ['manual' => false, 'xendit' => false];

        $isChannelActive = (bool) ($channelStatus[$balanceType] ?? false);
        if (!$isChannelActive) {
            return back()->withErrors([
                'balance_type' => 'Saluran Saldo ' . ucfirst($balanceType) . ' sedang dinonaktifkan oleh Admin Finance CIO. Silakan gunakan saluran saldo lainnya.'
            ])->withInput();
        }

        $availableBalance = (float) ($balanceType === 'manual' ? ($balanceData['balance_manual'] ?? 0) : ($balanceData['balance_xendit'] ?? 0));
        if ($grossAmount > $availableBalance) {
            return back()->withErrors([
                'amount' => 'Nominal transfer kotor (Rp ' . number_format($grossAmount, 0, ',', '.') . ') melebihi sisa Saldo ' . ucfirst($balanceType) . ' yang tersedia (Rp ' . number_format($availableBalance, 0, ',', '.') . ').'
            ])->withInput();
        }

        // Generate Referensi dengan Standar Prefix INV-
        $dateStr = now()->format('Ymd');
        $randomSeq = str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $code = "INV-TRF{$dateStr}{$randomSeq}";
        $financeRefId = "INV-DEDUCT-{$code}-" . time();

        $description = "Bagi hasil dividen {$investor->name} [{$code}]";
        $note = $request->notes ?: "Distribusi bagi hasil dividen periode " . Carbon::parse($request->transfer_date)->translatedFormat('F Y');

        // 2. Eksekusi Berdasarkan Tipe Saldo
        $xenditDisbursementId = null;
        $xenditStatus = null;

        if ($balanceType === 'manual') {
            // ALUR SALDO MANUAL: Langsung potong saldo manual di Finance API
            $deductResult = $financeService->deductBalance(
                amount: $grossAmount,
                balanceType: 'manual',
                referenceId: $financeRefId,
                description: $description,
                category: 'Dividen',
                note: $note
            );

            if (!$deductResult['success']) {
                return back()->withErrors([
                    'error' => 'Gagal memotong Saldo Manual pada Server Finance CIO: ' . $deductResult['message']
                ])->withInput();
            }

        } elseif ($balanceType === 'xendit') {
            // ALUR SALDO XENDIT:
            // Langkah 1: Potong Saldo Xendit di Finance API
            $deductResult = $financeService->deductBalance(
                amount: $grossAmount,
                balanceType: 'xendit',
                referenceId: $financeRefId,
                description: $description,
                category: 'Dividen',
                note: $note
            );

            if (!$deductResult['success']) {
                return back()->withErrors([
                    'error' => 'Gagal memotong Saldo Xendit pada Server Finance CIO: ' . $deductResult['message']
                ])->withInput();
            }

            // Langkah 2: Eksekusi Payout / Disbursement Xendit
            $firstInvRecord = $investor->investors->first() ?? $investor->investor;
            $bankAccount = $request->account_number ?: ($firstInvRecord->party_1_account_number ?? null);
            $accountHolder = $firstInvRecord->party_1_name ?? $investor->name;

            // Buat temporary transfer object untuk xendit service
            $tempTransfer = new Transfer([
                'id' => rand(1000, 9999),
                'code' => $code,
                'payment_method' => $request->payment_method,
            ]);

            $xenditResult = $xenditService->createPayout(
                transfer: $tempTransfer,
                investor: $investor,
                netAmount: $netAmount,
                bankCode: $request->payment_method,
                accountNumber: $bankAccount,
                accountHolderName: $accountHolder
            );

            if (!$xenditResult['status']) {
                // KOMPENSASI AUTO-REFUND: Kembalikan saldo Xendit ke Finance API jika payout gagal
                $refundRefId = "INV-REFUND-{$code}-" . time();
                $refundResult = $financeService->refundBalance(
                    amount: $grossAmount,
                    balanceType: 'xendit',
                    referenceId: $refundRefId,
                    description: "Rollback kegagalan transfer Xendit untuk {$investor->name} [{$code}]",
                    reason: $xenditResult['message']
                );

                $rollbackMsg = $refundResult['success'] 
                    ? 'Saldo Xendit berhasil dikembalikan secara otomatis (Auto-Refund).' 
                    : 'Peringatan: Gagal melakukan refund otomatis pada Finance API.';

                return back()->withErrors([
                    'error' => 'Transfer Xendit gagal: ' . $xenditResult['message'] . '. ' . $rollbackMsg
                ])->withInput();
            }

            $xenditDisbursementId = $xenditResult['disbursement_id'] ?? null;
            $xenditStatus = $xenditResult['data']['status'] ?? 'PENDING';
        }

        // Tentukan status awal & tanggal konfirmasi
        $status = 'pending';
        $confirmationDate = null;

        if ($balanceType === 'manual') {
            $status = 'success';
            $confirmationDate = now();
        } else {
            // Jika mode simulasi (mock), langsung set success
            if (!empty($xenditResult['is_simulated'])) {
                $status = 'success';
                $confirmationDate = now();
                $xenditStatus = 'COMPLETED';
            } else {
                // Real Xendit API awal payout berstatus PENDING
                $status = 'pending';
                $confirmationDate = null;
            }
        }

        // 3. Simpan Data Transfer ke Database
        $transfer = Transfer::create([
            'code'                   => $code,
            'admins_id'              => Auth::id(),
            'investors_id'           => $investor->id,
            'amount'                 => $netAmount,       // Nominal bersih yang diterima investor
            'gross_amount'           => $grossAmount,     // Nominal kotor dividen
            'admin_fee'              => $adminFee,        // Biaya admin yang dipotong
            'payment_method'         => $request->payment_method,
            'balance_type'           => $balanceType,
            'finance_reference_id'   => $financeRefId,
            'xendit_disbursement_id' => $xenditDisbursementId,
            'xendit_status'          => $xenditStatus,
            'transfer_date'          => $request->transfer_date,
            'confirmation_date'      => $confirmationDate,
            'notes'                  => $note,
            'status'                 => $status,
        ]);

        // 4. Catat Riwayat Transaksi Finansial ke Server Finance CIO (/api/v1/history)
        try {
            $financeService->recordTransferHistory($transfer);
        } catch (\Throwable $e) {
            Log::warning('Gagal mencatat log riwayat transfer ke Finance CIO: ' . $e->getMessage());
        }

        // 5. Kirim Notifikasi WhatsApp / Email otomatis
        // Hanya dikirim instan jika transfer sudah berstatus success (transfer manual / simulasi).
        // Untuk transfer Xendit asli (pending), notifikasi akan otomatis dikirim oleh Webhook Callback ketika payout COMPLETED.
        $notifResult = null;
        if ($status === 'success' && ($settings->notification_channel ?? 'whatsapp') !== 'none') {
            $notifResult = $qontakService->dispatchNotification($transfer);
        }

        if ($status === 'pending') {
            $successMsg = "Transfer dividen sebesar Rp " . number_format($netAmount, 0, ',', '.') . " berhasil diajukan ke Xendit (Status: PENDING). Pesan WhatsApp/Email ke investor akan otomatis terkirim segera setelah callback pembayaran berhasil diterima dari Xendit.";
        } else {
            $successMsg = "Transfer dividen sebesar Rp " . number_format($netAmount, 0, ',', '.') . " berhasil diproses (Sumber: Saldo " . ucfirst($balanceType) . "). Status: Lunas.";
            if ($notifResult && !empty($notifResult['message'])) {
                $successMsg .= " (" . $notifResult['message'] . ")";
            }
        }

        if ($adminFee > 0) {
            $successMsg .= " Potongan Biaya Admin: Rp " . number_format($adminFee, 0, ',', '.') . ".";
        }

        return redirect()->route('transfer.index')->with('success', $successMsg);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $investors = User::role('Investor')->with('investors')->orderBy('name', 'ASC')->get();
        $banks = Bank::orderBy('name', 'ASC')->get();

        $transfers = Transfer::with('investor')->findOrFail($id);

        return view("pages.transfer.edit", compact("investors", "banks", "transfers"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "investors_id" => "required",
            "amount" => "required",
            "payment_method" => "required",
            "transfer_date" => "required"
        ]);

        $transfer = Transfer::findOrFail($id);

        $amount = str_replace('.', '', $request->amount);

        $post = $request->all();
        $post['admins_id'] = Auth::user()->id;
        $post['amount'] = $amount;

        $transfer->update($post);

        return redirect()->route('transfer.index')->with('success', 'Berhasil melakukan perbaruan data transfer.');
    }

    /**
     * Kirim ulang notifikasi pesan WhatsApp dividen ke investor.
     */
    public function resendNotification(string $id, MekariQontakService $qontakService)
    {
        $transfer = Transfer::with('investor')->findOrFail($id);
        $result = $qontakService->dispatchNotification($transfer);

        if ($result['success']) {
            return back()->with('success', 'Notifikasi dividen berhasil dikirim ulang: ' . $result['message']);
        }

        return back()->with('error', 'Gagal mengirim ulang notifikasi: ' . $result['message']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transfer = Transfer::find($id);

        if (!$transfer) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }
        
        $transfer->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }

    public function confirmation(string $id) 
    {
        $transfers = Transfer::with(['investor'])->find($id);
        $transfers->update([
            "confirmation_date" => Carbon::now(),
            "status" => "success"
        ]);  

        $phone = Setting::first();
        
        $phoneFormatted = preg_replace('/^0/', '62', $phone->telp);
        $amount = number_format($transfers->amount);
        $transferDate = Carbon::parse($transfers->transfer_date)->format('d/M/Y');
        $confirmationDate = Carbon::parse($transfers->confirmation_date)->format('d/M/Y');

        $message = "*Konfirmasi Transfer-{$transfers->code}\n*"
                 . "*Nama Investor :* {$transfers->investor->name}\n"
                 . "*Nominal :* {$amount}\n"
                 . "*Bank :* {$transfers->payment_method}\n"
                 . "*Tanggal Transfer :* {$transferDate}\n"
                 . "*Tanggal Konfirmasi :* {$confirmationDate}\n";


        $encodeMessage = urlencode($message);

        $waLink = "https://wa.me/{$phoneFormatted}?text={$encodeMessage}";
       
        return redirect()->away($waLink)->with('success', 'Berhasil melaukan konfirmasi transfer pendapatan.');
    }

    public function export() 
    {
        return Excel::download(new TransferExport, 'transfer.xlsx');    
    }

    public function fetchAndStoreBanks(XenditService $xenditService)
    {
        $banks = $xenditService->getAllBanksFlat();

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['name' => $bank['name']]
            );
        }

        return response()->json([
            'message' => 'Data bank resmi Xendit berhasil disinkronkan ke database!',
            'total' => count($banks)
        ]);
    }
}
