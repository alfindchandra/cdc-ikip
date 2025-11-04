
@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="space-y-6">
    <!-- Filter & Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.siswa.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari NIS, NISN, atau Nama..." 
                           class="form-input">
                </div>
                <div>
                    <select name="kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <option value="X" {{ request('kelas') == 'X' ? 'selected' : '' }}>Kelas X</option>
                        <option value="XI" {{ request('kelas') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                        <option value="XII" {{ request('kelas') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                    </select>
                </div>
                <div>
                    <select name="jurusan" class="form-select">
                        <option value="">Semua Jurusan</option>
                        <option value="Rekayasa Perangkat Lunak" {{ request('jurusan') == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>RPL</option>
                        <option value="Teknik Komputer dan Jaringan" {{ request('jurusan') == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>TKJ</option>
                        <option value="Multimedia" {{ request('jurusan') == 'Multimedia' ? 'selected' : '' }}>MM</option>
                    </select>
                </div>
                <div class="flex space-x-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                Total: {{ $siswa->total() }} Siswa
            </h3>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.siswa.export') }}" class="btn btn-secondary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>No. Telp</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="font-medium">{{ $s->nis }}</td>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ substr($s->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $s->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $s->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $s->kelas }}</td>
                        <td>{{ Str::limit($s->jurusan, 20) }}</td>
                        <td>{{ $s->no_telp }}</td>
                        <td>
                            <span class="badge 
                                @if($s->status == 'aktif') badge-success
                                @elseif($s->status == 'lulus') badge-primary
                                @else badge-gray
                                @endif">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.siswa.show', $s->id) }}" 
                                   class="text-blue-600 hover:text-blue-700" 
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $s->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-700" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $s->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-700" 
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">Tidak ada data siswa</p>
                            <p class="text-sm mt-1">Silakan tambah data siswa baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswa->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $siswa->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

