<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicInvoiceController extends Controller
{
    /**
     * Display public dividend invoice / receipt using transferCode and secure token.
     */
    public function showDividen(Request $request)
    {
        $transferCode = $request->query('transferCode');
        $token        = $request->query('token');

        if (empty($transferCode) || empty($token)) {
            return view('pages.public_invoice.error', [
                'title'   => 'Tautan Tidak Lengkap',
                'message' => 'Parameter tautan invoice tidak valid atau belum lengkap. Pastikan Anda membuka tautan lengkap yang dikirimkan melalui WhatsApp.',
            ]);
        }

        $transfer = $this->resolveTransfer($transferCode);

        if (!$transfer) {
            return view('pages.public_invoice.error', [
                'title'   => 'Data Pembayaran Tidak Ditemukan',
                'message' => 'Data transfer dengan nomor referensi tersebut tidak ditemukan dalam sistem.',
            ]);
        }

        if (!$transfer->verifyInvoiceToken($token)) {
            return view('pages.public_invoice.error', [
                'title'   => 'Akses Ditolak (Token Tidak Valid)',
                'message' => 'Tanda tangan digital keamanan untuk faktur ini tidak cocok atau telah kedaluwarsa.',
            ]);
        }

        $setting = Setting::first();

        return view('pages.public_invoice.show', [
            'transfer' => $transfer,
            'setting'  => $setting,
        ]);
    }

    /**
     * Download PDF of dividend invoice using DomPDF.
     */
    public function downloadPdf(Request $request)
    {
        $transferCode = $request->query('transferCode');
        $token        = $request->query('token');

        if (empty($transferCode) || empty($token)) {
            abort(400, 'Parameter tidak lengkap.');
        }

        $transfer = $this->resolveTransfer($transferCode);

        if (!$transfer) {
            abort(404, 'Data transfer tidak ditemukan.');
        }

        if (!$transfer->verifyInvoiceToken($token)) {
            abort(403, 'Token tidak valid.');
        }

        $setting = Setting::first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.public_invoice.pdf', [
            'transfer' => $transfer,
            'setting'  => $setting,
        ])->setPaper('a5', 'portrait');

        $filename = 'Dividen-' . ($transfer->code ?? 'TRF-' . $transfer->id) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Resolve Transfer model by code or TRF-{id} pattern.
     */
    protected function resolveTransfer(string $transferCode): ?Transfer
    {
        $transfer = Transfer::with(['investor.investor', 'admin'])
            ->where('code', $transferCode)
            ->first();

        if (!$transfer && preg_match('/^TRF-?(\d+)$/i', $transferCode, $matches)) {
            $transfer = Transfer::with(['investor.investor', 'admin'])->find($matches[1]);
        }

        return $transfer;
    }
}
