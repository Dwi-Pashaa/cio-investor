@extends('layouts.app')

@section('title')
    Setting
@endsection

@push('css')
    
@endpush

@section('content')
    @include('components.alert.success')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('setting.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $settings->id ?? '' }}">
                <div class="form-group mb-3">
                    <label for="" class="mb-2">No Telephone (Wa)</label>
                    <input value="{{ $settings->telp ?? '' }}" type="text" name="telp" id="telp" class="form-control @error('telp') is-invalid @enderror">
                    @error('telp')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary float-end">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')

@endpush