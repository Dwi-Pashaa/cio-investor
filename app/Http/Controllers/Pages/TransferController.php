<?php

namespace App\Http\Controllers\Pages;

use App\Exports\TransferExport;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use App\Models\Transfer;
use App\Models\User;
use App\Services\MekariQontakService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $investors = User::role('Investor')->with('investors')->orderBy('name', 'ASC')->get();
        $banks = Bank::orderBy('name', 'ASC')->get();
        return view("pages.transfer.create", compact("investors", "banks"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MekariQontakService $qontakService)
    {
        $request->validate([
            "investors_id" => "required",
            "amount" => "required",
            "payment_method" => "required",
            "transfer_date" => "required"
        ]);

        $post = $request->all();

        $amount = str_replace('.', '', $request->amount);

        $post['admins_id'] = Auth::user()->id;
        $post['amount'] = $amount;

        $transfer = Transfer::create($post);

        // Kirim otomatis notifikasi pesan WhatsApp via Mekari Qontak Service
        $qontakResult = null;
        if ($request->boolean('send_wa', true)) {
            $qontakResult = $qontakService->sendDividendNotification($transfer);
        }

        $successMsg = 'Berhasil melakukan transfer pendapatan.';
        if ($qontakResult) {
            if ($qontakResult['success']) {
                $successMsg .= ' Pesan WhatsApp notifikasi dividen berhasil dikirim ke investor.';
            } else {
                $successMsg .= ' (Info WA: ' . $qontakResult['message'] . ')';
            }
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
        $result = $qontakService->sendDividendNotification($transfer);

        if ($result['success']) {
            return back()->with('success', 'Pesan notifikasi WhatsApp berhasil dikirim ke ' . $transfer->investor->name);
        }

        return back()->with('error', 'Gagal mengirim pesan WhatsApp: ' . $result['message']);
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

    public function fetchAndStoreBanks()
    {
        $apiKey = "JDJ5JDEzJFlQSWRScWh4OER5cXo5eVNkNXZzZ3UwM3kzbmh6aDVRcU5nMWFtek1Wd1daaFB2NjNTeU1T";
        $headers = [
            'Accept' => 'application/json; charset=UTF-8',
            'Authorization' => 'Basic ' . base64_encode($apiKey)
        ];

        $response = Http::withHeaders($headers)->get('https://bigflip.id/big_sandbox_api/v2/general/banks');

        if ($response->successful()) {
            $banks = $response->json();

            foreach ($banks as $bank) {
                Bank::updateOrCreate(
                    ['name' => $bank['name']] 
                );
            }

            return response()->json(['message' => 'Data bank berhasil diperbarui!']);
        }

        return response()->json(['error' => 'Gagal mengambil data bank'], 500);
    }
}
