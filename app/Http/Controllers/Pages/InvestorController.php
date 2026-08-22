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
                            $query->where(function($q) use ($search) {
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
        return view("pages.investor.create", compact("categories", "type", "existingInvestors", "selectedUserId"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->investor_mode === 'existing') {
            $request->validate([
                "users_id" => "required|exists:users,id",
                "categories_id" => "required",
                "types_id" => "required",
                "bussines_funds" => "required",
                "persentase" => "required",
                "file" => "required|file|mimes:pdf,doc,docx|max:10240"
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
                "password" => "required|min:8|confirmed",
                "password_confirmation" => "required",
                "file" => "required|file|mimes:pdf,doc,docx|max:10240"
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
            'file' => $path . $fileName
        ];

        Investor::create($postInvestor);

        return redirect()->route('investor.index')->with('success', 'Berhasil menambahkan data investasi.');
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
            "password" => "nullable|min:8|confirmed",
            "file" => "nullable|file|mimes:pdf,doc,docx|max:10240"
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

    public function export()
    {
        return Excel::download(new InvestorExport, 'investor.xlsx');
    }
}
