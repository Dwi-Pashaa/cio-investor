<?php

namespace App\Http\Controllers\Pages;

use App\Exports\InvestorExport;
use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Kategori;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $investors = User::role('Investor')
            ->with(['investors.type', 'investors.categorie'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate($sort);

        return view("pages.investor.index", compact("investors"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = Kategori::all();
        $type = Type::all();
        $existingInvestors = User::role('Investor')->with('investors')->orderBy('name', 'ASC')->get();
        $selectedUserId = $request->user_id ?? null;

        // Check if we have a previously created investor to show in preview
        $previewInvestor = null;
        if (session('last_created_investor_id') && session('show_preview_after_save')) {
            $previewInvestor = Investor::with(['user', 'categorie', 'type'])
                ->find(session('last_created_investor_id'));

            // Clear session data after loading
            session()->forget(['last_created_investor_id', 'show_preview_after_save']);
        }

        return view("pages.investor.create", compact("categories", "type", "existingInvestors", "selectedUserId", "previewInvestor"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi investor_mode terlebih dahulu
            $request->validate([
                'investor_mode' => 'required|in:new,existing',
            ]);

            if ($request->investor_mode === 'existing') {
                $request->validate([
                    "users_id" => "required|exists:users,id",
                    "categories_id" => "required",
                    "types_id" => "required",
                    "bussines_funds" => "required",
                    "persentase" => "required",
                    "first_money_received_at" => "nullable|date",
                    "first_dividend_at" => "nullable|date",
                    "last_dividend_at" => "nullable|date",
                    "party_1_name" => "nullable|string",
                    "party_1_address" => "nullable|string",
                    "party_1_bank" => "nullable|string|max:50",
                    "party_1_account_number" => "nullable|string|max:50",
                    "party_2_name" => "nullable|string",
                    "witness_1_name" => "nullable|string",
                    "witness_2_name" => "nullable|string",
                    "file" => "required|file|mimes:jpeg,png,jpg,webp|max:10240"
                ]);

                $userId = $request->users_id;
            } else {
                $request->validate([
                    "username" => "required|string|unique:users,username",
                    "name" => "required|string",
                    "email" => "required|string|unique:users,email",
                    "phone" => "nullable|string|max:20",
                    "categories_id" => "required",
                    "types_id" => "required",
                    "bussines_funds" => "required",
                    "persentase" => "required",
                    "first_money_received_at" => "nullable|date",
                    "first_dividend_at" => "nullable|date",
                    "last_dividend_at" => "nullable|date",
                    "party_1_name" => "nullable|string",
                    "party_1_address" => "nullable|string",
                    "party_1_bank" => "nullable|string|max:50",
                    "party_1_account_number" => "nullable|string|max:50",
                    "party_2_name" => "nullable|string",
                    "witness_1_name" => "nullable|string",
                    "witness_2_name" => "nullable|string",
                    "password" => "required|min:8|confirmed",
                    "password_confirmation" => "required",
                    "file" => "required|file|mimes:jpeg,png,jpg,webp|max:10240"
                ]);

                $postUser = $request->only("username", "name", "email", "phone");
                $postUser['password'] = Hash::make($request->password);

                $user = User::create($postUser);
                $user->assignRole("Investor");
                $userId = $user->id;
            }

            $bussines_funds = str_replace('.', '', $request->bussines_funds);
            $persentaseRaw = str_replace(',', '.', str_replace('%', '', $request->persentase));
            $persentaseDec = (float) $persentaseRaw / 100;

            $file = $request->file('file');
            $fileName = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $path = 'dokumen/';
            $file->move(public_path($path), $fileName);

            $postInvestor = [
                'users_id' => $userId,
                'categories_id' => $request->categories_id,
                'types_id' => $request->types_id,
                'bussines_funds' => $bussines_funds,
                'persentase' => $request->persentase,
                'monthly_income' => $bussines_funds * $persentaseDec,
                'first_money_received_at' => $request->first_money_received_at,
                'first_dividend_at' => $request->first_dividend_at,
                'last_dividend_at' => $request->last_dividend_at,
                'party_1_name' => $request->party_1_name,
                'party_1_address' => $request->party_1_address,
                'party_1_bank' => $request->party_1_bank,
                'party_1_account_number' => $request->party_1_account_number,
                'party_2_name' => $request->party_2_name ?? 'Cio Network',
                'witness_1_name' => $request->witness_1_name,
                'witness_2_name' => $request->witness_2_name,
                'party_1_signature' => $request->party_1_signature,
                'party_2_signature' => $request->party_2_signature,
                'witness_1_signature' => $request->witness_1_signature,
                'witness_2_signature' => $request->witness_2_signature,
                'file' => $path . $fileName
            ];

            $investor = Investor::create($postInvestor);

            // Simpan ID investor di session untuk preview PDF
            session(['last_created_investor_id' => $investor->id]);
            session(['show_preview_after_save' => true]);

            // Redirect ke halaman create dengan parameter untuk menampilkan step 5
            return redirect()->route('investor.create')
                ->with('success', 'Berhasil menambahkan data investasi.')
                ->with('show_preview', true)
                ->with('investor_id', $investor->id);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error storing investor: ' . $e->getMessage());

            // Kembalikan ke form dengan error message dan input data
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Kategori::all();
        $type = Type::all();
        $investor = Investor::with(['user'])->findOrFail($id);
        return view("pages.investor.edit", compact("categories", "type", "investor"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $investor = Investor::findOrFail($id);
        $user = $investor->user;

        $request->validate([
            "username" => "required|string|unique:users,username," . $user->id,
            "name" => "required|string",
            "email" => "required|string|unique:users,email," . $user->id,
            "phone" => "nullable|string|max:20",
            "categories_id" => "required",
            "types_id" => "required",
            "bussines_funds" => "required",
            "persentase" => "required",
            "first_money_received_at" => "nullable|date",
            "first_dividend_at" => "nullable|date",
            "last_dividend_at" => "nullable|date",
            "party_1_name" => "nullable|string",
            "party_2_name" => "nullable|string",
            "witness_1_name" => "nullable|string",
            "witness_2_name" => "nullable|string",
            "password" => "nullable|min:8|confirmed",
            "file" => "nullable|file|mimes:jpeg,png,jpg,webp|max:10240"
        ]);

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $bussines_funds = str_replace('.', '', $request->bussines_funds);
        $persentaseRaw = str_replace(',', '.', str_replace('%', '', $request->persentase));
        $persentaseDec = (float) $persentaseRaw / 100;

        $investor->categories_id = $request->categories_id;
        $investor->types_id = $request->types_id;
        $investor->bussines_funds = $bussines_funds;
        $investor->persentase = $request->persentase;
        $investor->monthly_income = $bussines_funds * $persentaseDec;
        $investor->first_money_received_at = $request->first_money_received_at;
        $investor->first_dividend_at = $request->first_dividend_at;
        $investor->last_dividend_at = $request->last_dividend_at;
        $investor->party_1_name = $request->party_1_name;
        $investor->party_1_address = $request->party_1_address;
        $investor->party_1_bank = $request->party_1_bank;
        $investor->party_1_account_number = $request->party_1_account_number;
        $investor->party_2_name = $request->party_2_name ?? 'Cio Network';
        $investor->witness_1_name = $request->witness_1_name;
        $investor->witness_2_name = $request->witness_2_name;

        if ($request->filled('party_1_signature')) {
            $investor->party_1_signature = $request->party_1_signature;
        }
        if ($request->filled('party_2_signature')) {
            $investor->party_2_signature = $request->party_2_signature;
        }
        if ($request->filled('witness_1_signature')) {
            $investor->witness_1_signature = $request->witness_1_signature;
        }
        if ($request->filled('witness_2_signature')) {
            $investor->witness_2_signature = $request->witness_2_signature;
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $path = 'dokumen/';
            $file->move(public_path($path), $fileName);
            $investor->file = $path . $fileName;
        }

        $investor->save();

        return redirect()->route('investor.index')->with('success', 'Data paket investasi berhasil diperbarui.');
    }

    /**
     * Remove the specified investment resource from storage.
     */
    public function destroyInvestment(string $id)
    {
        $investor = Investor::find($id);

        if (!$investor) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data investasi tidak ditemukan.']);
        }

        $investor->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus paket investasi.']);
    }

    /**
     * Remove the specified investor user and all investments from storage.
     */
    public function destroy(string $id)
    {
        $investor = Investor::find($id);

        if ($investor) {
            $investor->delete();
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data investasi.']);
        }

        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus investor.']);
        }

        return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data tidak ditemukan.']);
    }

    /**
     * Upload agreement document (PDF) for a specific investment.
     */
    public function uploadDocument(Request $request, $id)
    {
        try {
            $request->validate([
                "file" => "required|file|mimes:pdf|max:10240"
            ]);

            $investor = Investor::find($id);

            if (!$investor) {
                return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data investasi tidak ditemukan.']);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $path = 'dokumen/';
            $file->move(public_path($path), $fileName);

            // Hapus dokumen lama jika ada
            if ($investor->file && file_exists(public_path($investor->file))) {
                unlink(public_path($investor->file));
            }

            $investor->file = $path . $fileName;
            $investor->save();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Dokumen PDF berhasil diupload.',
                'file_url' => asset($investor->file)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['code' => 422, 'status' => 'errors', 'message' => $e->validator->errors()->first()]);
        } catch (\Exception $e) {
            \Log::error('Error uploading investor document: ' . $e->getMessage());
            return response()->json(['code' => 500, 'status' => 'errors', 'message' => 'Terjadi kesalahan saat mengupload dokumen.']);
        }
    }

    /**
     * Download PDF for a specific investor.
     */
    public function pdfDownload(string $id)
    {
        $investor = Investor::with(['user', 'categorie', 'type'])->find($id);

        if (!$investor) {
            return redirect()->back()->with('error', 'Data investor tidak ditemukan.');
        }

        $fileName = 'SPK-CIO-' . now()->format('Ym') . '-' . str_pad($investor->id, 3, '0', STR_PAD_LEFT) . '.pdf';
        $pdf = Pdf::loadView('pages.investor.pdf_template', compact('investor'))
            ->setPaper('a4')
            ->setOptions([
                'margin-top' => 35,
                'margin-right' => 32,
                'margin-bottom' => 32,
                'margin-left' => 32,
            ]);
        return $pdf->stream($fileName);
    }

    public function export()
    {
        return Excel::download(new InvestorExport, 'investor.xlsx');
    }
}
