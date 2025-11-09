@extends('layouts.app')

@section('title', 'Siswa PKL')
@section('page-title', 'Kelola Siswa PKL')

@section('content')
<div class="space-y-8"> {{-- Tingkatkan spacing --}}

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        {{-- Stat Card: Pengajuan --}}
        <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-yellow-500 transition duration-300 hover:shadow-xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Pengajuan</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pkl->where('status', 'pengajuan')->count() }}</p>
            <a href="?status=pengajuan" class="text-xs text-yellow-500 hover:text-yellow-700 font-medium mt-1 inline-block">Lihat Daftar →</a>
        </div>
        
        {{-- Stat Card: Diterima --}}
        <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-blue-500 transition duration-300 hover:shadow-xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Status Diterima</p>
            <p class="text-3xl font-bold text-blue-600">{{ $pkl->where('status', 'diterima')->count() }}</p>
            <a href="?status=diterima" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-1 inline-block">Lihat Daftar →</a>
        </div>
        
        {{-- Stat Card: Berlangsung --}}
        <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-green-500 transition duration-300 hover:shadow-xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Sedang Berlangsung</p>
            <p class="text-3xl font-bold text-green-600">{{ $pkl->where('status', 'berlangsung')->count() }}</p>
            <a href="?status=berlangsung" class="text-xs text-green-500 hover:text-green-700 font-medium mt-1 inline-block">Lihat Daftar →</a>
        </div>
        
        {{-- Stat Card: Selesai --}}
        <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-gray-500 transition duration-300 hover:shadow-xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Telah Selesai</p>
            <p class="text-3xl font-bold text-gray-600">{{ $pkl->where('status', 'selesai')->count() }}</p>
            <a href="?status=selesai" class="text-xs text-gray-500 hover:text-gray-700 font-medium mt-1 inline-block">Lihat Daftar →</a>
        </div>
    </div>

    ---

    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa (NIM & Prodi)</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Periode PKL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Posisi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pkl as $p)
                    <tr class="hover:bg-indigo-50/50 transition duration-150 ease-in-out">
                        {{-- Kolom Siswa --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold flex-shrink-0 text-lg">
                                    {{-- Menggunakan warna Indigo untuk identitas perusahaan --}}
                                    {{ substr($p->siswa->user->name, 0, 1) }}
                                </div>
                                <div class="truncate">
                                    <p class="font-semibold text-gray-900 truncate">{{ $p->siswa->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $p->siswa->nis }} - {{ $p->siswa->program_studi }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Kolom Periode --}}
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <p class="text-sm font-medium text-gray-900">{{ $p->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">s/d {{ $p->tanggal_selesai->format('d M Y') }}</p>
                        </td>

                        {{-- Kolom Posisi --}}
                        <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                            {{ $p->posisi ?? 'Belum Ditentukan' }}
                        </td>

                        {{-- Kolom Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="
                                @php
                                    $statusClass = [
                                        'pengajuan' => 'bg-yellow-100 text-yellow-800',
                                        'diterima' => 'bg-blue-100 text-blue-800',
                                        'berlangsung' => 'bg-green-100 text-green-800',
                                        'selesai' => 'bg-gray-100 text-gray-800',
                                        'ditolak' => 'bg-red-100 text-red-800',
                                    ];
                                    $currentStatusClass = $statusClass[strtolower($p->status)] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $currentStatusClass }}
                            ">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        
                        {{-- Kolom Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('perusahaan.pkl.show', $p->id) }}" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out font-semibold flex items-center justify-end">
                                Detail
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500 bg-gray-50">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-lg font-semibold text-gray-700">Tidak ada data siswa PKL.</p>
                            <p class="text-sm mt-1">Belum ada pengajuan atau siswa yang terdaftar di perusahaan Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pkl->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pkl->links() }}
        </div>
        @endif
    </div>
</div>
@endsection