@extends('layouts.app')

@section('title', 'Lowongan Kerja')
@section('page-title', 'Kelola Lowongan Kerja')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Total: {{ $lowongan->total() }} Lowongan</h3>
        </div>
        <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Lowongan Baru
        </a>
    </div>

    <!-- Lowongan List -->
    <div class="space-y-4">
        @forelse($lowongan as $l)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $l->judul }}</h3>
                            <span class="badge badge-{{ $l->status == 'aktif' ? 'success' : ($l->status == 'expired' ? 'danger' : 'gray') }}">
                                {{ ucfirst($l->status) }}
                            </span>
                        </div>

                        <p class="text-gray-600 mb-4">{{ $l->posisi }}</p>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Tipe Pekerjaan</p>
                                <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $l->tipe_pekerjaan)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="font-semibold text-gray-900">{{ $l->lokasi }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Pelamar</p>
                                <p class="font-semibold text-gray-900">{{ $l->jumlah_pelamar }} orang</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Batas Akhir</p>
                                <p class="font-semibold text-gray-900">{{ $l->tanggal_berakhir->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('perusahaan.lowongan.show', $l->id) }}" class="btn btn-outline btn-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('perusahaan.lowongan.edit', $l->id) }}" class="btn btn-secondary btn-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('perusahaan.lowongan.toggle', $l->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-{{ $l->status == 'aktif' ? 'warning' : 'success' }} btn-sm">
                                    {{ $l->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form action="{{ route('perusahaan.lowongan.destroy', $l->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Belum ada lowongan dibuat</p>
                <p class="text-sm text-gray-500 mb-4">Mulai posting lowongan untuk menarik kandidat terbaik</p>
                <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-primary">
                    Buat Lowongan Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if($lowongan->hasPages())
    <div class="flex justify-center">
        {{ $lowongan->links() }}
    </div>
    @endif
</div>
@endsection