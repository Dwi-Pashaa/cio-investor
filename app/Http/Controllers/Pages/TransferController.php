<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
                                        $q->where('name', 'like', "%$search%");
                                    })
                                    ->orWhereHas('admin', function ($q) use ($search) {
                                        $q->where('name', 'like', "%$search%");
                                    })
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
        $investors = User::role('Investor')->get();
        $banks = Bank::orderBy('name', 'ASC')->get();
        return view("pages.transfer.create", compact("investors", "banks"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        Transfer::create($post);

        return redirect()->route('transfer.index')->with('success', 'Berhasil melakukan transfer.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $investors = User::role('Investor')->get();
        $banks = Bank::orderBy('name', 'ASC')->get();

        $transfers = Transfer::find($id);

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

        return redirect()->route('transfer.index')->with('success', 'Berhasil melakukan transfer ulang.');
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
        $transfers = Transfer::find($id);
        $transfers->update([
            "confirmation_date" => Carbon::now(),
            "status" => "success"
        ]);  

        return back()->with('success', 'Berhasil konfirmasi transfer.');
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
