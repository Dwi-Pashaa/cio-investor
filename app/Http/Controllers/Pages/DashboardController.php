<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\Setting;
use App\Models\Transfer;
use App\Models\User;
use App\Services\CioFinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected CioFinanceService $financeService;

    public function __construct(CioFinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request) 
    {
        $grafikPendapatan = User::role('Investor')->with(['investors', 'transfer'])->get();
        
        $investorsCount = User::role('Investor')->count();
        $jumlahDanaInvestasi = Investor::sum('bussines_funds');

        $userId = Auth::user()->id;

        $myInvestments = Investor::where('users_id', $userId)->with(['type', 'categorie'])->get();

        $dataPendapatan = Transfer::selectRaw('MONTH(transfer_date) as bulan, SUM(amount) as total_pendapatan')
            ->where('investors_id', $userId)
            ->where('status', '=', 'success')
            ->groupBy('bulan')
            ->pluck('total_pendapatan', 'bulan');

        $bulanLengkap = [
            1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April",
            5 => "Mei", 6 => "Juni", 7 => "Juli", 8 => "Agustus",
            9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
        ];

        $listPendapatanBulanan = collect($bulanLengkap)->map(function ($namaBulan, $index) use ($dataPendapatan) {
            return [
                'bulan' => $namaBulan,
                'nominal' => $dataPendapatan[$index] ?? 0
            ];
        });

        $dashboardColumns = Setting::dashboardColumns();

        $freshBalance = $request->has('refresh_balance');
        $financeBalance = Auth::user()->hasRole('Admin') 
            ? $this->financeService->getBalance($freshBalance)
            : ['status' => 'not_permitted', 'message' => 'Hanya admin yang memiliki izin.', 'data' => []];

        return view("pages.dashboard", compact(
            'grafikPendapatan',
            'listPendapatanBulanan',
            'jumlahDanaInvestasi',
            'investorsCount',
            'myInvestments',
            'dashboardColumns',
            'financeBalance'
        ));
    }
}
