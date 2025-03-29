@extends('layouts.app')

@section('title')
    Edit Investor
@endsection

@push('css')
    
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('investor.index') }}" class="btn btn-primary">
                <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11 7l-5 5l5 5" /><path d="M17 7l-5 5l5 5" /></svg>
                Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('investor.update', ['id' => $investor->user->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Username</label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ $investor->user->username }}">
                            @error('username')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Nama Investor</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ $investor->user->name }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Email Investor</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ $investor->user->email }}">
                            @error('email')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Kategori Kerjasama</label>
                            <select name="categories_id" id="categories_id" class="form-control @error('categories_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($categories as $ct)
                                    <option value="{{ $ct->id }}" {{ $investor->categories_id == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Total Dana Usaha</label>
                            <input type="text" name="bussines_funds" id="bussines_funds" class="form-control @error('bussines_funds') is-invalid @enderror" value="{{ $investor->bussines_funds }}">
                            @error('bussines_funds')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                        <div class="form-group mb-2">
                            <label for="" class="mb-2">Pendapatan Bulanan <span class="text-muted">(Persentase)</span></label>
                            <input type="text" name="persentase" id="persentase" class="form-control @error('persentase') is-invalid @enderror" value="{{ $investor->persentase }}">
                            @error('persentase')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Type Pendapatan</label>
                            <select name="types_id" id="types_id" class="form-control @error('types_id') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach ($type as $tp)
                                    <option value="{{ $tp->id }}" {{ $investor->types_id == $tp->id ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                            @error('types_id')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label for="" class="mb-2">Upload Dokumen</label>
                            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror">
                            @error('file')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-2">
                            <label for="" class="mb-2">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <span class="text-muted">Kosongkan jika tidak mengubah password</span>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-2">
                            <label for="" class="mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <span class="text-muted">Kosongkan jika tidak mengubah password</span>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="reset" class="btn btn-secondary float-start">Reset</button>
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
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

        $(document).ready(function() {
            let initialValue = $("#bussines_funds").val();
            if (initialValue) {
                $("#bussines_funds").val(formatRupiah(initialValue));
            }

            $("#bussines_funds").on("keyup", function() {
                let value = $(this).val();
                $(this).val(formatRupiah(value));
            });
        });
    </script>
@endpush