@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="alert alert-primary">
        <b>Selamat Datang {{ Auth::user()->name }}</b>
    </div>
    <div class="row row-cards mb-3">
        <div class="col-sm-6 col-lg-6">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Jumlah Investor
                            </div>
                            <div class="text-secondary">
                                {{ $investorsCount }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M3 8a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M18 12h.01" /><path d="M6 12h.01" /></svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                Total Dana Investasi
                            </div>
                            <div class="text-secondary">
                                Rp. {{ number_format($jumlahDanaInvestasi) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 mb-2">
            <div class="card">
                <div class="card-header">
                    <b>Grafik Investasi</b>
                </div>
                <div class="card-body">
                    <div id="chart-income"></div>
                </div>
                <div class="table-responsive-lg">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th>Nama Investor</th>
                                <th>Dana Invest</th>
                                <th>Persentase</th>
                                <th>Nominal Pendapatan</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grafikPendapatan as $dp)
                                <tr>
                                    <td>{{ $dp->name }}</td>
                                    <td>Rp. {{ number_format($dp->investor->bussines_funds) }}</td>
                                    <td>{{ $dp->investor->persentase }}%</td>
                                    <td>Rp. {{ number_format($dp->investor->monthly_income) }}</td>
                                    <td>
                                        @php
                                            $sudahDibayar = $dp->transfer()
                                                ->whereMonth('transfer_date', now()->month)
                                                ->whereYear('transfer_date', now()->year)
                                                ->where('status', 'success')
                                                ->exists();
                                        @endphp
                                        @if ($sudahDibayar)
                                            <span class="badge bg-success text-white">Sudah Dibayar</span>
                                        @else
                                            <span class="badge bg-danger text-white">Belum Dibayar</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak Ada Investor</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (Auth::user()->hasRole('Investor'))
            <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header">
                        <p class="mt-2">
                            Pendapatan Bulanan Pada Tahun {{ date('Y') }}
                            <br>
                            <span class="text-muted" style="font-size: 12px">Paling lambat dibyarkan pada tanggal 1 - 10</span>
                        </p>
                    </div>
                    <div class="table-responsive-lg">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listPendapatanBulanan as $data)
                                    <tr>
                                        <td>{{ $data['bulan'] }}</td>
                                        <td>Rp. {{ number_format($data['nominal'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header">
                        <b>Dokumen</b>
                    </div>
                    <div class="card-body">
                        <embed src="{{ Auth::user()->investor->file }}" type="application/pdf" width="100%" height="600px" />
                    </div>
                </div>
            </div>
        @else
            
        @endif
    </div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var data = {!! json_encode($grafikPendapatan->map(function($item) {
            $sudahDibayar = $item->transfer()
                ->whereMonth('transfer_date', now()->month)
                ->whereYear('transfer_date', now()->year)
                ->where('status', 'success')
                ->exists();
            
            return [
                'name' => $item->name,
                'funds' => $item->investor->bussines_funds ?? 0,
                'status' => $sudahDibayar ? 'paid' : 'unpaid'
            ];
        })) !!};
    
        var options = {
            chart: {
                type: "pie",
                height: 500,
                fontFamily: "inherit",
            },
            series: data.map(item => item.funds),
            labels: data.map(item => item.name),
            tooltip: {
                theme: "dark",
                y: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat().format(value);
                    }
                },
                style: {
                    fontSize: "12px",
                    fontFamily: "inherit",
                    colors: ["#FFFFFF"] 
                }
            },
            legend: {
                position: "right",
                fontSize: "12px",
                markers: {
                    width: 12,
                    height: 12
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                },
                formatter: function(seriesName, opts) {
                    var status = data[opts.seriesIndex].status;
                    var icon = status === 'paid' 
                        ? `<svg width="14" height="14" viewBox="0 0 24 24" fill="green" style="margin-left: 5px;">
                                <path d="M9 16.2l-4.2-4.2-1.4 1.4 5.6 5.6 10.6-10.6-1.4-1.4z"></path>
                           </svg>` 
                        : `<svg width="14" height="14" viewBox="0 0 24 24" fill="red" style="margin-left: 5px;">
                                <path d="M18 6L6 18M6 6l12 12" stroke="red" stroke-width="2"></path>
                           </svg>`;
    
                    return `<span style="display: flex; align-items: center;">
                                ${seriesName} ${icon}
                            </span>`;
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: "12px",
                    colors: ["#FFFFFF"]
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 1,
                    opacity: 0.6
                }
            }
        };
    
        var chart = new ApexCharts(document.getElementById("chart-income"), options);
        chart.render();
    
        function checkScreenSize() {
            if (window.innerWidth < 768) {
                chart.updateOptions({
                    legend: {
                        show: false 
                    }
                });
            } else {
                chart.updateOptions({
                    legend: {
                        show: true 
                    }
                });
            }
        }
    
        checkScreenSize();
        window.addEventListener("resize", checkScreenSize);
    });
    </script>
@endpush