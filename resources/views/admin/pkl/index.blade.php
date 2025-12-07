@extends('layouts.app')

@section('title', 'Kelola PKL')
@section('page-title', 'Pengelolaan PKL')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">
    
    {{-- Filter & Search Card (Menggunakan style modern yang sama) --}}
    <div class="bg-white shadow-xl rounded-xl p-6 border-t-4 border-blue-600">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Filter Data Praktik Kerja Lapangan (PKL)</h3>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4 md:gap-6 items-end">
            {{-- Search Input --}}
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Cari...</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Cari nama siswa atau perusahaan..." 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1 sr-only">Status PKL</label>
                <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150 appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            
            {{-- Filter Button --}}
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 shadow-md transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Stats Cards (Enhanced & dynamic coloring) --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        @php
            $stats = [
                'pengajuan' => ['label' => 'Pengajuan', 'color' => 'yellow', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                'diterima' => ['label' => 'Diterima', 'color' => 'blue', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.416A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 4.644m15.236 0A12 12 0 0112 11c-1.332 0-2.65-.246-3.834-.727m15.236 0l-1.5 7.5a3 3 0 01-2.492 2.215H6.91a3 3 0 01-2.492-2.215L3 18.028"/>'],
                'berlangsung' => ['label' => 'Berlangsung', 'color' => 'indigo', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'selesai' => ['label' => 'Selesai', 'color' => 'green', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'],
                'ditolak' => ['label' => 'Ditolak', 'color' => 'red', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'],
            ];
        @endphp
        @foreach($stats as $statusKey => $stat)
        <div class="bg-white shadow-xl rounded-2xl p-5 text-center border-l-4 border-{{ $stat['color'] }}-500 transition duration-300 hover:shadow-2xl">
            <div class="w-10 h-10 bg-{{ $stat['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
            </div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $stat['label'] }}</p>
            <p class="text-3xl font-extrabold text-{{ $stat['color'] }}-600">
                {{ $pkl->where('status', $statusKey)->count() }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- PKL Table (Modernized) --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Siswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Periode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pkl as $p)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-semibold text-gray-900">{{ $p->siswa->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $p->siswa->nis }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $p->siswa->kelas }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $p->perusahaan->nama_perusahaan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-800">{{ $p->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">s/d {{ $p->tanggal_selesai->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{-- Dynamic Badge --}}
                            @php
                                $status_classes = [
                                    'pengajuan' => 'bg-yellow-100 text-yellow-800',
                                    'diterima' => 'bg-blue-100 text-blue-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'berlangsung' => 'bg-indigo-100 text-indigo-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                ];
                                $current_class = $status_classes[$p->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold {{ $current_class }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 items-center">
                                {{-- Detail Button --}}
                                <a href="{{ route('admin.pkl.show', $p->id) }}" 
                                    class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-100 transition duration-150" 
                                    title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.pkl.destroy', $p->id) }}"
                                    class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-100 transition duration-150" 
                                    title="Hapus PKL" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?');">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                                {{-- Jurnal Button --}}
                                <a href="{{ route('admin.pkl.jurnal', $p->id) }}" 
                                    class="text-indigo-600 hover:text-indigo-800 p-1 rounded-full hover:bg-indigo-100 transition duration-150" 
                                    title="Lihat Jurnal">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                {{-- Tambahkan tombol Edit/Delete lainnya di sini --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-gray-500 bg-gray-50">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-xl font-semibold">Tidak ada data PKL yang ditemukan</p>
                            <p class="text-sm mt-1">Coba sesuaikan filter pencarian Anda.</p>
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