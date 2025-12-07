@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-book me-2"></i>Jurnal PKL</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.pkl.index') }}">PKL</a></li>
                    <li class="breadcrumb-item active">Jurnal</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.pkl.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Info PKL -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi PKL</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="fw-bold">Nama Siswa</td>
                            <td>{{ $pkl->siswa->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">NIM</td>
                            <td>{{ $pkl->siswa->nim }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Fakultas/Prodi</td>
                            <td>
                                {{ $pkl->siswa->fakultas->nama ?? '-' }} / 
                                {{ $pkl->siswa->programStudi->nama ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <td width="40%" class="fw-bold">Perusahaan</td>
                            <td>{{ $pkl->perusahaan->nama_perusahaan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Periode PKL</td>
                            <td>
                                {{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($pkl->tanggal_selesai)->format('d M Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status</td>
                            <td>
                                @if($pkl->status == 'berlangsung')
                                    <span class="badge bg-success">Berlangsung</span>
                                @elseif($pkl->status == 'selesai')
                                    <span class="badge bg-info">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($pkl->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Jurnal -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jurnal</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pkl->jurnalPkl->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Disetujui</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $pkl->jurnalPkl->where('status_validasi', 'disetujui')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $pkl->jurnalPkl->where('status_validasi', 'pending')->count() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $pkl->jurnalPkl->where('status_validasi', 'ditolak')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Jurnal -->
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Jurnal Kegiatan</h5>
        </div>
        <div class="card-body">
            @if($pkl->jurnalPkl->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Belum ada jurnal yang diinput oleh siswa.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">Tanggal</th>
                                <th>Kegiatan</th>
                                <th width="10%">Foto</th>
                                <th width="12%">Status</th>
                                <th width="15%">Catatan Pembimbing</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pkl->jurnalPkl as $jurnal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $jurnal->kegiatan }}</td>
                                    <td class="text-center">
                                        @if($jurnal->foto)
                                            <a href="{{ asset('storage/' . $jurnal->foto) }}" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-image"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($jurnal->status_validasi == 'disetujui')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($jurnal->status_validasi == 'ditolak')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $jurnal->catatan_pembimbing ?? '-' }}</small>
                                    </td>
                                    <td>
                                        @if($jurnal->status_validasi == 'pending')
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#validasiModal{{ $jurnal->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#validasiModal{{ $jurnal->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif

                                        <!-- Modal Validasi -->
                                        <div class="modal fade" id="validasiModal{{ $jurnal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.pkl.jurnal.validasi', $jurnal->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Validasi Jurnal</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Tanggal</label>
                                                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Kegiatan</label>
                                                                <textarea class="form-control" rows="3" readonly>{{ $jurnal->kegiatan }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label required">Status Validasi</label>
                                                                <select name="status_validasi" class="form-select" required>
                                                                    <option value="disetujui" {{ $jurnal->status_validasi == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                                                    <option value="ditolak" {{ $jurnal->status_validasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                                    <option value="pending" {{ $jurnal->status_validasi == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Catatan Pembimbing</label>
                                                                <textarea name="catatan_pembimbing" class="form-control" rows="3" placeholder="Berikan catatan atau feedback...">{{ $jurnal->catatan_pembimbing }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="fas fa-save me-2"></i>Simpan Validasi
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert('{{ session("success") }}');
        });
    </script>
@endif
@endsection