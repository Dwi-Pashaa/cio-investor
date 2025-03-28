<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Kategori;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sort = $request->sort ?? 10;
        $search = $request->search ?? null;

        $investors = Investor::whereHas('user.roles', function ($query) {
                                    $query->where('name', 'Investor');
                                })
                                ->with(['user', 'type', 'categorie'])
                                ->when($search, function ($query, $search) {
                                    $query->whereHas('user', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%")
                                        ->orWhere('username', 'like', "%$search%");
                                    })
                                    ->orWhereHas('type', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%");
                                    })
                                    ->orWhereHas('categorie', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%");
                                    });
                                })
                                ->orderBy('id', 'DESC')
                                ->paginate($sort);
    

        return view("pages.investor.index", compact("investors"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Kategori::all();
        $type = Type::all();
        return view("pages.investor.create", compact("categories", "type"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "username" => "required|string|unique:users,username",
            "name" => "required|string",
            "email" => "required|string|unique:users,email",
            "categories_id" => "required",
            "types_id" => "required",
            "bussines_funds" => "required",
            "persentase" => "required",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required"
        ]);

        $postUser = $request->only("username", "name", "email", "password");
        $postUser['password'] = Hash::make($request->password);

        $user = User::create($postUser);
        $user->assignRole("Investor");

        $bussines_funds = str_replace('.', '', $request->bussines_funds);
        $persentase = str_replace(',', '.', str_replace('%', '', $request->persentase)); 
        $persentase = (float) $persentase / 100;

        $postInvestor = $request->only("categories_id", "types_id", "bussines_funds", "persentase");
        $postInvestor['persentase'] = $request->persentase;
        $postInvestor['bussines_funds'] = $bussines_funds;
        $postInvestor['monthly_income'] = $bussines_funds * $persentase;
        $postInvestor['users_id'] = $user->id;

        Investor::create($postInvestor);

        return redirect()->route('investor.index')->with('success', 'Berhasil menyimpan data investor.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Kategori::all();
        $type = Type::all();
        $investor = Investor::with(['user'])->find($id);
        return view("pages.investor.edit", compact("categories", "type", "investor"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            "username" => "required|string|unique:users,username," . $user->id,
            "name" => "required|string",
            "email" => "required|string|unique:users,email," . $user->id,
            "categories_id" => "required",
            "types_id" => "required",
            "bussines_funds" => "required",
            "persentase" => "required",
            "password" => "nullable|min:8|confirmed",
        ]);

        $postUser = $request->only("username", "name", "email");

        if ($request->filled('password')) {
            $postUser['password'] = Hash::make($request->password);
        }

        $user->update($postUser);

        if (!$user->hasRole("Investor")) {
            $user->assignRole("Investor");
        }

        $bussines_funds = str_replace('.', '', $request->bussines_funds);
        $persentase = str_replace(',', '.', str_replace('%', '', $request->persentase));
        $persentase = (float) $persentase / 100;

        $postInvestor = $request->only("categories_id", "types_id");
        $postInvestor['persentase'] = $request->persentase;
        $postInvestor['bussines_funds'] = $bussines_funds;
        $postInvestor['monthly_income'] = $bussines_funds * $persentase;

        if ($user->investor) {
            $user->investor->update($postInvestor);
        } else {
            $postInvestor['users_id'] = $user->id;
            Investor::create($postInvestor);
        }

        return redirect()->route('investor.index')->with('success', 'Data investor berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['code' => 400, 'status' => 'errors', 'message' => 'Data Not Found.']);
        }
        
        $user->delete();

        return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Berhasil menghapus data.']);
    }
}
