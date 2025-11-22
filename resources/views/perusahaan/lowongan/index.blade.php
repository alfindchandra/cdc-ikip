@extends('layouts.app')

@section('title', 'Lowongan Kerja')
@section('page-title', 'Kelola Lowongan Kerja')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h3 class="text-xl font-semibold text-gray-900">
            Total: <span class="text-blue-600">{{ $lowongan->total() }}</span> Lowongan
        </h3>

        <a href="{{ route('perusahaan.lowongan.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Lowongan Baru
        </a>
    </div>

    <!-- Daftar Lowongan -->
    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
        @forelse($lowongan as $l)
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
            <div class="p-6 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-bold text-gray-900">{{ $l->judul }}</h3>
                        <span class="
                            px-3 py-1 text-sm font-medium rounded-full
                            {{ $l->status == 'aktif' ? 'bg-green-100 text-green-700' : ($l->status == 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($l->status) }}
                        </span>
                    </div>

                    <p class="text-gray-600 text-sm mb-4">{{ $l->posisi }}</p>

                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Tipe</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $l->tipe_pekerjaan)) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Lokasi</p>
                            <p class="font-semibold text-gray-900">{{ $l->lokasi }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Pelamar</p>
                            <p class="font-semibold text-gray-900">{{ $l->jumlah_pelamar }} orang</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Batas Akhir</p>
                            <p class="font-semibold text-gray-900">{{ $l->tanggal_berakhir->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('perusahaan.lowongan.show', $l->id) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 hover:text-blue-700 hover:border-blue-300 rounded-lg text-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>

                    <a href="{{ route('perusahaan.lowongan.edit', $l->id) }}" 
                       class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 hover:bg-gray-200 rounded-lg text-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                  a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>

                    <form action="{{ route('perusahaan.lowongan.status', $l->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 
                                {{ $l->status == 'aktif' ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            {{ $l->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <form action="{{ route('perusahaan.lowongan.destroy', $l->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')" 
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-sm transition-all duration-200">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 
                                      01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 
                                      00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 bg-white border border-gray-200 rounded-2xl">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 
                      012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 
                      01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-lg font-semibold text-gray-800 mb-2">Belum ada lowongan dibuat</p>
            <p class="text-sm text-gray-500 mb-6">Mulai posting lowongan untuk menarik kandidat terbaik.</p>
            <a href="{{ route('perusahaan.lowongan.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v16m8-8H4"/>
                </svg>
                Buat Lowongan Pertama
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lowongan->hasPages())
    <div class="flex justify-center mt-8">
        {{ $lowongan->links() }}
    </div>
    @endif
</div>
@endsection
