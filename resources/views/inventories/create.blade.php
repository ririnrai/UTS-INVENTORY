@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h1 class="h4 mb-3">Tambah Barang Inventori</h1>

        <form action="{{ route('inventories.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control @error('nama_barang') is-invalid @enderror">
                    @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">No Barang</label>
                    <input type="number" name="no_barang" value="{{ old('no_barang') }}" class="form-control @error('no_barang') is-invalid @enderror">
                    @error('no_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah Barang</label>
                    <input type="number" name="jumlah_barang" value="{{ old('jumlah_barang', 1) }}" class="form-control @error('jumlah_barang') is-invalid @enderror" min="0">
                    @error('jumlah_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Barang</label>
                    <select name="jenis_barang" class="form-select @error('jenis_barang') is-invalid @enderror">
                        <option value="">Pilih jenis</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ old('jenis_barang') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('jenis_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Masuk/Keluar</label>
                    <input type="date" name="tanggal_masuk_keluar" value="{{ old('tanggal_masuk_keluar', now()->format('Y-m-d')) }}" class="form-control @error('tanggal_masuk_keluar') is-invalid @enderror">
                    @error('tanggal_masuk_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="">Pilih role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Session</label>
                    <input type="number" name="session" value="{{ old('session', 1) }}" class="form-control @error('session') is-invalid @enderror" min="1">
                    @error('session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Timestamp</label>
                    <input type="time" name="timestamp" value="{{ old('timestamp', now()->format('H:i')) }}" class="form-control @error('timestamp') is-invalid @enderror">
                    @error('timestamp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date Session</label>
                    <input type="datetime-local" name="date_session" value="{{ old('date_session', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('date_session') is-invalid @enderror">
                    @error('date_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Simpan Barang</button>
                <a href="{{ route('inventories.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
