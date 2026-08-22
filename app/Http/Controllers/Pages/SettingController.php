<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() 
    {
        $settings = Setting::first();
        $dashboardColumns = Setting::dashboardColumns();
        return view("pages.setting.index", compact("settings", "dashboardColumns"));    
    }

    public function store(Request $request) 
    {
        $request->validate([
            "telp" => "required|string"
        ]);

        Setting::updateOrCreate(
            [],
            ["telp" => $request->telp]
        );

        return back()->with('success', 'Berhasil menyimpan pengaturan kontak.');
    }

    public function saveDashboardColumns(Request $request)
    {
        $columns = [];
        $allowedKeys = ['dana_investasi', 'persentase', 'nominal_pendapatan', 'status_pembayaran'];

        foreach ($allowedKeys as $key) {
            $columns[$key] = [
                'visible' => $request->has("columns.$key") ? true : false
            ];
        }

        Setting::updateOrCreate([], ['dashboard_columns' => $columns]);

        return back()->with('success', 'Pengaturan kolom dashboard berhasil disimpan.');
    }
}
