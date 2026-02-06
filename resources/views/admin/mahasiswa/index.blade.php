@extends('layouts.app')

@section('title', 'Data Mahamahasiswa')
@section('page-title', 'Data Mahamahasiswa')

@section('content')
<div class="space-y-8">
    <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="col-span-1 md:col-span-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="🔍 Cari NIM atau Nama..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            >
        </div>
        
        <div>
            <select name="fakultas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Semua Fakultas</option>
                @foreach ($fakultas as $f)
                    <option value="{{ $f->id }}" {{ request('fakultas') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <select name="program_studi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Semua Program Studi</option>
               @foreach ($program_studi as $ps)
                    <option value="{{ $ps->id }}" {{ request('program_studi') == $ps->id ? 'selected' : '' }}>{{ $ps->nama }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Cuti" {{ request('status') == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
            </select>
        </div>
        
        <div class="col-span-1 md:col-span-5 flex space-x-2">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Filter/Cari
            </button>
            <a href="{{ route('admin.mahasiswa.index') }}" 
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg px-4 py-2 text-center transition flex items-center justify-center">
               <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11.418 5a8.001 8.001 0 01-15.356 2A8.001 8.001 0 0119.418 15m0 0H15"></path></svg>
                Reset
            </a>
        </div>
    </form>
</div>

    <!-- Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h3 class="text-lg font-semibold text-gray-800">
            Total: <span class="text-blue-600">{{ $mahasiswa->total() }}</span> Mahamahasiswa
        </h3>
        <div class="flex flex-wrap gap-3">
            
           
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-50 text-gray-700 uppercase text-sm font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">NIM</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Fakultas</th>
                        <th class="px-6 py-3 text-left">Program Studi</th>
                        <th class="px-6 py-3 text-left">No. Telp</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($mahasiswa as $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">{{ $s->nim }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                                    {{ substr($s->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $s->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $s->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $s->fakultas->nama ?? '-' }}</td> 
        <td class="px-6 py-4">{{ $s->programStudi->nama ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $s->no_telp }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($s->status == 'aktif') bg-green-100 text-green-700
                                @elseif($s->status == 'lulus') bg-blue-100 text-blue-700
                                @else bg-gray-100 text-gray-600
                                @endif">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('admin.mahasiswa.show', $s->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.mahasiswa.edit', $s->id) }}" class="text-yellow-500 hover:text-yellow-600" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.mahasiswa.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium">Tidak ada data Mahamahasiswa</p>
                            <p class="text-sm text-gray-500">Silakan tambah data mahamahasiswa baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mahasiswa->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $mahasiswa->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
