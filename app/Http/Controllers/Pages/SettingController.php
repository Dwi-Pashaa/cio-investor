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
        return view("pages.setting.index", compact("settings"));    
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

        return back()->with('success', 'Berhasil menyimpan setting.');
    }
}
