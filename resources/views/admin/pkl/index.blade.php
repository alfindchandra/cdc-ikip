@extends('layouts.app')

@section('title', 'Kelola PKL')
@section('page-title', 'Pengelolaan PKL')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa atau perusahaan..." class="form-input">
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach(['pengajuan' => 'warning', 'diterima' => 'primary', 'berlangsung' => 'info', 'selesai' => 'success', 'ditolak' => 'danger'] as $status => $color)
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600 mb-1">{{ ucfirst($status) }}</p>
                <p class="text-2xl font-bold text-{{ $color }}-600">
                    {{ $pkl->where('status', $status)->count() }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- PKL Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Perusahaan</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pkl as $p)
                    <tr class="hover:bg-gray-50">
                        <td>
                            <div>
                                <p class="font-medium text-gray-900">{{ $p->siswa->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $p->siswa->nis }}</p>
                            </div>
                        </td>
                        <td>{{ $p->perusahaan->nama_perusahaan }}</td>
                        <td>
                            <p class="text-sm">{{ $p->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">s/d {{ $p->tanggal_selesai->format('d M Y') }}</p>
                        </td>
                        <td>
                            <span class="badge 
                                @if($p->status == 'pengajuan') badge-warning
                                @elseif($p->status == 'diterima') badge-primary
                                @elseif($p->status == 'berlangsung') badge-info
                                @elseif($p->status == 'selesai') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.pkl.show', $p->id) }}" class="text-blue-600 hover:text-blue-700" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.pkl.jurnal', $p->id) }}" class="text-green-600 hover:text-green-700" title="Jurnal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500">
                            Tidak ada data PKL
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pkl->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pkl->links() }}
        </div>
        @endif
    </div>
</div>
@endsection