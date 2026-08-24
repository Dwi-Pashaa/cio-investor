<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perjanjian Kerjasama Investasi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: A4;
            margin: 30mm 25mm 28mm 25mm; /* atas kanan bawah kiri - lebih longgar dari kertas */
        }

        html, body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #1a1a1a;
            line-height: 1.8;
        }

        body {
            padding: 0 20mm; /* buffer tambahan supaya teks tidak mepet margin cetak */
            padding-top: 25mm;
            padding-bottom: 25mm;
            padding-left: 25mm;
            padding-right: 25mm;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1a1a1a;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .header .logo-cell,
        .header .info-cell {
            display: table-cell;
            vertical-align: middle;
        }
        .header .logo-cell {
            width: 100px;
            padding-right: 14px;
        }
        .logo-box {
            width: 80px;
            height: 80px;
            border: 1px solid #1a1a1a;
            text-align: center;
            font-size: 9pt;
            line-height: 80px;
            color: #888;
        }
        .header .info-cell {
            text-align: center;
        }
        .header .company-name {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header .company-type {
            font-size: 10pt;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }
        .header .company-address {
            font-size: 9pt;
            color: #555;
            margin-top: 3px;
        }

        .doc-title-wrap {
            text-align: center;
            margin-bottom: 22px;
        }
        .doc-title {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-decoration: underline;
        }
        .nomor {
            font-size: 10pt;
            color: #555;
            margin-top: 6px;
        }

        p {
            margin: 0 0 14px 0;
            text-align: justify;
            font-size: 12pt;
            page-break-inside: auto;
            orphans: 3;
            widows: 3;
        }

        .premis-list p {
            margin: 0 0 10px 22px;
            text-indent: -14px;
            padding-left: 14px;
        }

        table.pihak {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0 18px 24px;
            page-break-inside: avoid;
        }
        table.pihak td {
            padding: 4px 8px;
            vertical-align: top;
            font-size: 12pt;
        }
        table.pihak td:first-child { width: 24px; }
        table.pihak td:nth-child(2) { width: 130px; font-weight: bold; }
        table.pihak td:nth-child(3) { width: 14px; }
        table.pihak tr.spacer td { padding: 8px 0; }

        .pasal-box {
            margin: 26px 0 20px 0;
            page-break-inside: auto;
        }
        .pasal-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 12px;
            text-align: center;
            letter-spacing: 0.5px;
            page-break-after: avoid;
        }
        .ayat {
            margin: 0 0 12px 0;
            text-align: justify;
            line-height: 1.8;
            font-size: 12pt;
            page-break-inside: auto;
            orphans: 3;
            widows: 3;
        }

        .signatures {
            margin-top: 60px;
            page-break-inside: avoid;
        }
        .sig-grid {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .sig-grid td {
            width: 25%;
            text-align: center;
            padding: 6px 12px;
            vertical-align: top;
        }
        .sig-label {
            font-size: 9pt;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .sig-image-box {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .sig-image-box img {
            max-height: 75px;
            max-width: 100%;
        }
        .sig-placeholder {
            font-size: 9pt;
            color: #aaa;
            font-style: italic;
        }
        .sig-name {
            font-size: 10pt;
            font-weight: bold;
            border-top: 1px solid #1a1a1a;
            padding-top: 6px;
            margin-top: 8px;
            display: inline-block;
            min-width: 110px;
        }

        .footer-note {
            margin-top: 30px;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    @php
        function terbilang($number) {
            $number = (int) $number;
            $words = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
            if ($number < 0) {
                return 'minus ' . terbilang(abs($number));
            }
            if ($number < 12) {
                return $words[$number];
            }
            if ($number < 20) {
                return terbilang($number - 10) . ' belas';
            }
            if ($number < 100) {
                return terbilang(intval($number / 10)) . ' puluh ' . terbilang($number % 10);
            }
            if ($number < 200) {
                return 'seratus ' . terbilang($number - 100);
            }
            if ($number < 1000) {
                return terbilang(intval($number / 100)) . ' ratus ' . terbilang($number % 100);
            }
            if ($number < 2000) {
                return 'seribu ' . terbilang($number - 1000);
            }
            if ($number < 1000000) {
                return terbilang(intval($number / 1000)) . ' ribu ' . terbilang($number % 1000);
            }
            if ($number < 1000000000) {
                return terbilang(intval($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
            }
            if ($number < 1000000000000) {
                return terbilang(intval($number / 1000000000)) . ' miliar ' . terbilang($number % 1000000000);
            }
            return terbilang(intval($number / 1000000000000)) . ' triliun ' . terbilang($number % 1000000000000);
        }

        $agreementDate = $investor->created_at
            ? \Carbon\Carbon::parse($investor->created_at)->translatedFormat('l, d F Y')
            : now()->translatedFormat('l, d F Y');
        $companyName = $investor->party_2_name ?? 'Cio Network';
        $companyType = $investor->categorie->name ?? '-';
        $companyAddress = $investor->company_address ?? ($investor->party_1_address ?? '-');
        $partyOneName = $investor->party_1_name ?? $investor->user->name;
        $partyTwoName = $investor->party_2_name ?? 'Cio Network';
        $partyOneEmail = $investor->user->email ?? '-';
        $partyOneAddress = $investor->party_1_address ?? '-';
        $partyTwoAddress = $investor->party_2_address ?? '-';
        $modalAmount = $investor->bussines_funds ?? 0;
        $monthlyIncome = $investor->monthly_income ?? 0;
        $netIncome = round($monthlyIncome * 0.55);
        $penaltyAmount = round($modalAmount * 0.2);
        $modalTerbilang = trim(ucwords(terbilang($modalAmount))) . ' Rupiah';
        $incomeTerbilang = trim(ucwords(terbilang($monthlyIncome))) . ' Rupiah';
        $netIncomeTerbilang = trim(ucwords(terbilang($netIncome))) . ' Rupiah';
        $penaltyTerbilang = trim(ucwords(terbilang($penaltyAmount))) . ' Rupiah';
        $persentaseValue = (float) str_replace(',', '.', str_replace('%', '', $investor->persentase ?? 0));
        $firstDividendDate = $investor->first_dividend_at
            ? \Carbon\Carbon::parse($investor->first_dividend_at)->translatedFormat('l, d F Y')
            : '-';
        $monthlyDueDate = $investor->last_dividend_at
            ? \Carbon\Carbon::parse($investor->last_dividend_at)->translatedFormat('d')
            : '-';
        $kopImage = 'data:image/png;base64,' . base64_encode(@file_get_contents(public_path('img/kop.png')));
    @endphp

    <div class="header">
        <img src="{{ $kopImage }}" alt="Kop Surat" style="width: 100%; display: block;">
    </div>

    <div class="doc-title-wrap">
        <div class="doc-title">Surat Perjanjian Kerjasama Investasi</div>
        <div class="nomor">Nomor: SPK/CIO/{{ now()->format('mY') }}/{{ str_pad($investor->id, 3, '0', STR_PAD_LEFT) }}</div>
    </div>

    <p>Pada hari ini, <strong>{{ $agreementDate }}</strong>, kami yang bertanda tangan di bawah ini:</p>

    <table class="pihak">
        <tr>
            <td>1.</td>
            <td>Nama</td>
            <td>:</td>
            <td><strong>{{ $partyOneName }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Status</td>
            <td>:</td>
            <td>Pemilik Dana Investasi</td>
        </tr>
        <tr>
            <td></td>
            <td>Email</td>
            <td>:</td>
            <td>{{ $partyOneEmail }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $partyOneAddress }}</td>
        </tr>
        <tr>
            <td></td>
            <td>No. Rekening</td>
            <td>:</td>
            <td>{{ $investor->party_1_bank ?? '-' }} {{ isset($investor->party_1_account_number) && $investor->party_1_account_number ? $investor->party_1_account_number : '' }}</td>
        </tr>
        <tr class="spacer"><td colspan="4"></td></tr>
        <tr>
            <td>2.</td>
            <td>Nama</td>
            <td>:</td>
            <td><strong>Yoga Ogawa Rhobiyana</strong></td>
        </tr>
        <tr>
            <td></td>
            <td>Status</td>
            <td>:</td>
            <td>Direktur Utama / Pemilik Usaha</td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat KTP</td>
            <td>:</td>
            <td>Kp. Legok Kaso, Rt.001, Rw.004, Des. Cinanggela, Kec. Pacet, Bandung.</td>
        </tr>
        <tr>
            <td></td>
            <td>No Telp</td>
            <td>:</td>
            <td>085324780031</td>
        </tr>
        <tr>
            <td></td>
            <td>No Rekening</td>
            <td>:</td>
            <td>BRI 3774-01-026538-50-2</td>
        </tr>
    </table>

    <p>Selanjutnya PIHAK PERTAMA dan PIHAK KEDUA secara bersama-sama disebut sebagai Para Pihak.</p>

    <p>Bahwa sebelum ditandatanganinya Surat Perjanjian ini, Para Pihak terlebih dahulu menerangkan hal-hal sebagai berikut:</p>

    <div class="premis-list">
        <p>1. Bahwa PIHAK PERTAMA adalah selaku INVESTOR yang memiliki modal sebesar Rp {{ number_format($modalAmount, 0, ',', '.') }} ({{ $modalTerbilang }}) untuk selanjutnya disebut sebagai MODAL INVESTASI untuk proyek di bidang {{ $companyType }} sesuai KBLI {{ $investor->kbli_code ?? '61994' }}.</p>
        <p>2. Bahwa PIHAK KEDUA adalah selaku pengelola DANA INVESTASI di bidang {{ $companyType }} sesuai KBLI {{ $investor->kbli_code ?? '61994' }} yang berlokasi di {{ $companyAddress }}.</p>
        <p>3. Bahwa PIHAK PERTAMA dan PIHAK KEDUA setuju untuk saling mengikatkan diri dalam suatu Perjanjian Kerjasama Investasi sesuai ketentuan hukum yang berlaku.</p>
        <p>4. Bahwa berdasarkan hal-hal tersebut di atas, kedua belah pihak menyatakan sepakat dan setuju untuk mengadakan Perjanjian Kerjasama ini yang dilaksanakan dengan ketentuan dan syarat-syarat sebagai berikut.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL I<br>MAKSUD DAN TUJUAN</div>
        <p>PIHAK PERTAMA dalam perjanjian ini memberi DANA INVESTASI kepada PIHAK KEDUA sebesar Rp {{ number_format($modalAmount, 0, ',', '.') }} ({{ $modalTerbilang }}) dan PIHAK KEDUA dengan ini telah menerima penyerahan DANA INVESTASI tersebut dari PIHAK PERTAMA serta menyanggupi untuk melaksanakan pengelolaan DANA INVESTASI tersebut.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL II<br>RUANG LINGKUP</div>
        <p class="ayat">1. Dalam pelaksanaan perjanjian ini, PIHAK PERTAMA memberi DANA INVESTASI kepada PIHAK KEDUA sebesar Rp {{ number_format($modalAmount, 0, ',', '.') }} ({{ $modalTerbilang }}) dan PIHAK KEDUA dengan ini telah menerima penyerahan DANA INVESTASI tersebut dari PIHAK PERTAMA serta menyanggupi untuk melaksanakan pengelolaan DANA INVESTASI.</p>
        <p class="ayat">2. PIHAK KEDUA dengan ini berjanji dan mengikatkan diri untuk melaksanakan perputaran DANA INVESTASI pada usaha di bidang {{ $companyType }} sesuai KBLI {{ $investor->kbli_code ?? '61994' }} yang berlokasi di {{ $companyAddress }} setelah ditandatanganinya perjanjian ini.</p>
        <p class="ayat">3. PIHAK KEDUA dengan ini berjanji dan mengikatkan diri untuk memberikan keuntungan {{ number_format($persentaseValue, 0) }}% ({{ ucwords(terbilang((int) $persentaseValue)) }} Persen) atau sebesar Rp {{ number_format($monthlyIncome, 0, ',', '.') }} ({{ $incomeTerbilang }}), dengan pembagian profit sesuai kesepakatan Para Pihak.</p>
        <p class="ayat">4. PIHAK PERTAMA akan mendapatkan keuntungan pertama kalinya pada tanggal {{ $firstDividendDate }}.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL III<br>JANGKA WAKTU KERJASAMA</div>
        <p class="ayat">1. Perjanjian Kerjasama ini dilakukan untuk jangka waktu tidak terbatas, serta mendapatkan keuntungan terhitung sejak pertama kali PIHAK PERTAMA menerima keuntungan dengan periode jatuh tempo pada tanggal {{ $monthlyDueDate }} setiap bulannya.</p>
        <p class="ayat">2. Pengembalian DANA INVESTASI dapat dilakukan setelah 12 ( Dua Belas ) bulan, atau setelah memperoleh keuntungan minimal 12 ( Dua Belas ) kali, dengan pemberitahuan tertulis minimal 1 ( Satu ) bulan sebelum permohonan pengembalian DANA INVESTASI kepada PIHAK KEDUA.</p>
        <p class="ayat">3. Apabila Pihak Pertama mengajukan permohonan pengembalian DANA INVESTASI sebelum 12 ( Dua Belas ) bulan, atau 12 ( Dua Belas ) kali mendapatkan keuntungan, terhitung sejak pertama kali Pihak Pertama menerima keuntungan {{ number_format($persentaseValue, 0) }}% ({{ ucwords(terbilang((int) $persentaseValue)) }} Persen) atau sebesar Rp.{{ number_format($monthlyIncome, 0, ',', '.') }} ({{ $incomeTerbilang }}) Di kurangi 45% (Empat Puluh Lima Persen) profit total Pihak Pertama Mendapatkan Total 55% (Lima Puluh Lima Persen) Sebesar Rp{{ number_format($netIncome, 0, ',', '.') }} ({{ $netIncomeTerbilang }}), maka Pihak Pertama akan dikenakan penalty sebesar 20% (Dua Puluh Persen) atau sebesar Rp.{{ number_format($penaltyAmount, 0, ',', '.') }} ({{ $penaltyTerbilang }}) dari keseluruhan DANA INVESTASI.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL IV<br>HAK DAN KEWAJIBAN PIHAK PERTAMA</div>
        <p>PIHAK PERTAMA berkewajiban memberikan dana investasi sebagaimana Pasal I, berhak menerima keuntungan pertama sesuai Pasal II, berhak menerima hasil keuntungan pengelolaan sesuai Pasal VI, serta berhak meminta pengembalian dana penuh setelah memenuhi syarat sebagaimana Pasal III.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL V<br>HAK DAN KEWAJIBAN PIHAK KEDUA</div>
        <p>PIHAK KEDUA menerima dana investasi dari PIHAK PERTAMA, wajib memberikan bagian keuntungan sesuai Pasal VI, berhak mengembalikan dana setelah syarat terpenuhi sesuai Pasal III, serta wajib konsisten mempertahankan kesepakatan perjanjian ini.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL VI<br>PEMBAGIAN HASIL</div>
        <p class="ayat">1. Pembagian keuntungan dilakukan melalui rekening masing-masing pihak sesuai Pasal II.</p>
        <p class="ayat">2. Perhitungan bagi hasil mengacu pada nominal yang disebutkan pada Pasal II.</p>
        <p class="ayat">3. Bagi hasil berlaku sampai PIHAK PERTAMA menarik kembali dana investasinya sesuai Pasal III.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL VII<br>KEADAAN MEMAKSA (FORCE MAJEUR)</div>
        <p class="ayat">1. Keadaan memaksa adalah kejadian di luar kemampuan Para Pihak seperti bencana alam, banjir, badai, gempa bumi, kebakaran, perang, huru-hara, demonstrasi, dan/atau kebijakan pemerintah yang mengakibatkan perjanjian tidak dapat dilaksanakan.</p>
        <p class="ayat">2. Apabila perjanjian terhambat karena keadaan memaksa, PIHAK KEDUA wajib mengembalikan dana investasi secara penuh kepada PIHAK PERTAMA.</p>
        <p class="ayat">3. Tata cara pengembalian dana dimusyawarahkan oleh Para Pihak.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL VIII<br>WANPRESTASI</div>
        <p class="ayat">1. Pelanggaran kewajiban oleh salah satu pihak dianggap wanprestasi tanpa perlu pembuktian lebih lanjut.</p>
        <p class="ayat">2. Pihak yang dirugikan berhak meminta ganti rugi, kecuali pelanggaran disebabkan keadaan memaksa.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL IX<br>PERSELISIHAN</div>
        <p>Perselisihan yang timbul dari perjanjian ini diselesaikan secara musyawarah mufakat. Apabila tidak tercapai kesepakatan, Para Pihak sepakat untuk menyelesaikan melalui Kantor Kepaniteraan Pengadilan Negeri {{ $investor->legal_domicile ?? 'Bandung' }}.</p>
    </div>

    <div class="pasal-box">
        <div class="pasal-title">PASAL X<br>ATURAN PENUTUP</div>
        <p>Hal-hal yang belum diatur dalam perjanjian ini akan ditetapkan melalui addendum yang mengikat Para Pihak dan menjadi bagian tidak terpisahkan dari perjanjian ini. Perjanjian ini dibuat rangkap 2 (dua) bermaterai cukup dan berlaku sejak ditandatangani.</p>
    </div>

    <p>Demikian Surat Perjanjian Kerjasama Investasi ini dibuat dan ditandatangani oleh Para Pihak dalam keadaan sadar, sehat, tanpa adanya paksaan dari pihak manapun.</p>

    <!-- Signature Section -->
    <div class="signatures">
        <table class="sig-grid">
            <tr>
                <td>
                    <div class="sig-label">Pihak Pertama<br>(Investor)</div>
                    <div class="sig-image-box">
                        @if($investor->party_1_signature)
                            <img src="{{ $investor->party_1_signature }}" alt="TTD Pihak 1">
                        @else
                            <span class="sig-placeholder">(Tanda Tangan)</span>
                        @endif
                    </div>
                    <div><span class="sig-name">{{ $investor->party_1_name ?? $investor->user->name }}</span></div>
                </td>
                <td>
                    <div class="sig-label">Pihak Kedua<br>(Pengelola)</div>
                    <div class="sig-image-box">
                        @if($investor->party_2_signature)
                            <img src="{{ $investor->party_2_signature }}" alt="TTD Pihak 2">
                        @else
                            <span class="sig-placeholder">(Tanda Tangan)</span>
                        @endif
                    </div>
                    <div><span class="sig-name">{{ $investor->party_2_name ?? 'Cio Network' }}</span></div>
                </td>
                <td>
                    <div class="sig-label">Saksi<br>Pihak Pertama</div>
                    <div class="sig-image-box">
                        @if($investor->witness_1_signature)
                            <img src="{{ $investor->witness_1_signature }}" alt="TTD Saksi 1">
                        @else
                            <span class="sig-placeholder">(Tanda Tangan)</span>
                        @endif
                    </div>
                    <div><span class="sig-name">{{ $investor->witness_1_name ?? '-' }}</span></div>
                </td>
                <td>
                    <div class="sig-label">Saksi<br>Pihak Kedua</div>
                    <div class="sig-image-box">
                        @if($investor->witness_2_signature)
                            <img src="{{ $investor->witness_2_signature }}" alt="TTD Saksi 2">
                        @else
                            <span class="sig-placeholder">(Tanda Tangan)</span>
                        @endif
                    </div>
                    <div><span class="sig-name">{{ $investor->witness_2_name ?? '-' }}</span></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dokumen ini dibuat secara digital oleh sistem Cio Network &bull;
        Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

</body>
</html>
