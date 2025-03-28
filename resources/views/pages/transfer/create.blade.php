@extends('layouts.app')

@section('title')
    Buat Transfer Pendapatan
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('transfer.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Pilih Investor</label>
                            <select name="investors_id" id="investors_id" class="form-control @error('investors_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($investors as $ivt)
                                    <option value="{{ $ivt->id }}" {{ old('investors_id') == $ivt->id ? 'selected' : '' }}>{{ $ivt->name }}</option>
                                @endforeach
                            </select>
                            @error('investors_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Jumlah Pendapatan</label>
                            <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}">
                            @error('amount')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Pilih Jenis Bank</label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($banks as $bnk)
                                    <option value="{{ $bnk->name }}" {{ old('payment_method') == $bnk->name ? 'selected' : '' }}>{{ $bnk->name }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Tanggal Transfer</label>
                            <input type="date" name="transfer_date" id="transfer_date" class="form-control @error('transfer_date') is-invalid @enderror" value="{{ date('Y-m-d') }}">
                            @error('transfer_date')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Catatan <span class="text-muted">(Bisa dikosongkan)</span></label>
                            <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes') }}">
                            @error('notes')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="reset" class="btn btn-secondary float-start">Reset</button>
                    <button type="submit" class="btn btn-primary float-end">Transfer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $("#investors_id").select2({
            width: "100%",
        });

        $("#payment_method").select2({
            width: "100%"
        });

        function formatRupiah(angka) {
            if (!angka) return "";
            
            let numberString = angka.toString().replace(/\D/g, ""),
                sisa = numberString.length % 3,
                rupiah = numberString.substr(0, sisa),
                ribuan = numberString.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            return rupiah;
        }

        $("#amount").keyup(function() {
            let value = $(this).val();

            $("#amount").val(formatRupiah(value))
        });
    </script>
@endpush