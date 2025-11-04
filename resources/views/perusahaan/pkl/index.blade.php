@extends('layouts.app')

@section('title', 'Siswa PKL')
@section('page-title', 'Kelola Siswa PKL')

@section('content')
<div class="space-y-6">
    <!-- Filter & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Pengajuan</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pkl->where('status', 'pengajuan')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Diterima</p>
                <p class="text-2xl font-bold text-blue-600">{{ $pkl->where('status', 'diterima')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Berlangsung</p>
                <p class="text-2xl font-bold text-green-600">{{ $pkl->where('status', 'berlangsung')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Selesai</p>
                <p class="text-2xl font-bold text-gray-600">{{ $pkl->where('status', 'selesai')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- PKL List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Periode</th>
                        <th>Posisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pkl as $p)
                    <tr class="hover:bg-gray-50">
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ substr($p->siswa->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $p->siswa->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $p->siswa->nis }} - {{ $p->siswa->jurusan }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-sm font-medium text-gray-900">{{ $p->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-sm text-gray-500">s/d {{ $p->tanggal_selesai->format('d M Y') }}</p>
                        </td>
                        <td>{{ $p->posisi ?? '-' }}</td>
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
                            <a href="{{ route('perusahaan.pkl.show', $p->id) }}" class="text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada siswa PKL</p>
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

