@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h4">Dashboard Inventori</h1>
                <p class="text-muted mb-0">Kelola barang dan simulasikan pengeluaran inventori dengan FIFO / LIFO.</p>
            </div>
            <a href="{{ route('inventories.create') }}" class="btn btn-success">Tambah Barang</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row gy-4">
            <div class="col-lg-5">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">Simulasi Pengeluaran</div>
                    <div class="card-body">
                        <form action="{{ route('inventories.outflow') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Jenis Barang</label>
                                <select name="jenis_barang" class="form-select @error('jenis_barang') is-invalid @enderror">
                                    <option value="">Pilih jenis</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('jenis_barang') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('jenis_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Keluar</label>
                                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" class="form-control @error('quantity') is-invalid @enderror" min="1">
                                @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Metode</label>
                                <select name="method" class="form-select @error('method') is-invalid @enderror">
                                    <option value="fifo" {{ old('method') === 'fifo' ? 'selected' : '' }}>FIFO</option>
                                    <option value="lifo" {{ old('method') === 'lifo' ? 'selected' : '' }}>LIFO</option>
                                </select>
                                @error('method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Dasar Urutan</label>
                                <select name="basis" class="form-select @error('basis') is-invalid @enderror">
                                    @foreach($bases as $key => $label)
                                        <option value="{{ $key }}" {{ old('basis') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('basis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Session</label>
                                <input type="number" name="session" value="{{ old('session', 1) }}" class="form-control @error('session') is-invalid @enderror" min="1">
                                @error('session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label class="form-label">Timestamp</label>
                                    <input type="time" name="timestamp" value="{{ old('timestamp', now()->format('H:i')) }}" class="form-control @error('timestamp') is-invalid @enderror">
                                    @error('timestamp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date Session</label>
                                    <input type="datetime-local" name="date_session" value="{{ old('date_session', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('date_session') is-invalid @enderror">
                                    @error('date_session')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <button class="btn btn-primary w-100">Proses Pengeluaran</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">Ringkasan Stok</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>No Barang</th>
                                        <th>Jumlah</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Role</th>
                                        <th>Session</th>
                                        <th>Timestamp</th>
                                        <th>Date Session</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventories as $inventory)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $inventory->nama_barang }}</td>
                                            <td>{{ $inventory->no_barang }}</td>
                                            <td>{{ $inventory->jumlah_barang }}</td>
                                            <td>{{ $inventory->jenis_barang }}</td>
                                            <td>{{ $inventory->tanggal_masuk_keluar?->format('Y-m-d') }}</td>
                                            <td>{{ $inventory->role }}</td>
                                            <td>{{ $inventory->session }}</td>
                                            <td>{{ $inventory->timestamp }}</td>
                                            <td>{{ $inventory->date_session?->format('Y-m-d H:i') }}</td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                <form action="{{ route('inventories.destroy', $inventory) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus barang ini?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center py-4">Belum ada data inventori.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
