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
        <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
            <div class="card">
                <div class="card-header">
                    <b>Grafik Investasi</b>
                </div>
                <div class="card-body">
                    <div id="chart-income"></div>
                </div>
            </div>
        </div>
        @if (Auth::user()->hasRole('Investor'))
            <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                <div class="card">
                    <div class="card-header">
                        <b>Pendapatan Bulanan Pada Tahun {{ date('Y') }}</b>
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
        @else
            
        @endif
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                chart: {
                    type: "pie",
                    height: 500,
                    fontFamily: "inherit",
                },
                series: {!! json_encode($grafikPendapatan->map(fn($item) => $item->investor->bussines_funds ?? 0)) !!},
                labels: {!! json_encode($grafikPendapatan->map(fn($item) => $item->name)) !!},
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