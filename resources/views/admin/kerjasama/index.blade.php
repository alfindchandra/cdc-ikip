@extends('layouts.app')

@section('title', 'Kerjasama Industri')
@section('page-title', 'Kerjasama Industri')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">

    <div class="bg-white shadow-lg rounded-xl">
        <div class="p-5">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Filter & Pencarian</h2>
            <form method="GET" action="{{ route('admin.kerjasama.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Cari Kerjasama</label>
                    <input type="text"
                           id="search-input"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Judul atau perusahaan..."
                           class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="jenis-select" class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                    <select id="jenis-select" name="jenis" class="form-select w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Jenis</option>
                        <option value="pkl" {{ request('jenis') == 'pkl' ? 'selected' : '' }}>PKL</option>
                        <option value="rekrutmen" {{ request('jenis') == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                        <option value="pelatihan" {{ request('jenis') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="penelitian" {{ request('jenis') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                        <option value="sponsorship" {{ request('jenis') == 'sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                        <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label for="status-select" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status-select" name="status" class="form-select w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="proposal" {{ request('status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                        <option value="negosiasi" {{ request('status') == 'negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 transition duration-150">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.kerjasama.index') }}" class="btn btn-outline w-full sm:w-auto flex items-center justify-center border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg px-4 py-2 transition duration-150">Reset</a>
                </div>
            </form>
        </div>
    </div>

    ---

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $stats = [
                ['status' => 'Draft', 'value' => $kerjasama->where('status', 'draft')->count(), 'color' => 'gray', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['status' => 'Proposal', 'value' => $kerjasama->where('status', 'proposal')->count(), 'color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['status' => 'Negosiasi', 'value' => $kerjasama->where('status', 'negosiasi')->count(), 'color' => 'yellow', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['status' => 'Aktif', 'value' => $kerjasama->where('status', 'aktif')->count(), 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['status' => 'Selesai', 'value' => $kerjasama->where('status', 'selesai')->count(), 'color' => 'purple', 'icon' => 'M5 13l4 4L19 7'],
                ['status' => 'Batal', 'value' => $kerjasama->where('status', 'batal')->count(), 'color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="card bg-white p-4 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
            <div class="text-center">
                <div class="w-10 h-10 bg-{{ $stat['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1">{{ $stat['status'] }}</p>
                <p class="text-2xl font-extrabold text-{{ $stat['color'] }}-600">{{ $stat['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    ---

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">
                Total: **{{ $kerjasama->total() }}** Kerjasama
            </h3>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.kerjasama.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Kerjasama Baru
            </a>
        </div>
    </div>

    ---

    <div class="card bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kerjasama as $k)
                    <tr class="hover:bg-indigo-50/50 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <p class="font-medium text-gray-900 truncate max-w-xs">{{ $k->judul }}</p>
                                <p class="text-sm text-indigo-600 font-medium">{{ $k->perusahaan->nama_perusahaan }}</p>
                                @if($k->nilai_kontrak)
                                <p class="text-xs text-green-600 font-semibold mt-1">
                                    Nilai: Rp {{ number_format($k->nilai_kontrak, 0, ',', '.') }}
                                </p>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <span class="badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ ucfirst($k->jenis_kerjasama) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <p class="font-medium">{{ $k->tanggal_mulai->format('d M Y') }}</p>
                            @if($k->tanggal_berakhir)
                            <p class="text-xs text-gray-500">s/d {{ $k->tanggal_berakhir->format('d M Y') }}</p>
                            @else
                            <p class="text-xs text-gray-500 italic">Tidak terbatas</p>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                            <div class="space-y-1">
                                @if($k->pic_sekolah)
                                <p><span class="text-xs text-gray-500">Sekolah:</span> **{{ $k->pic_sekolah }}**</p>
                                @endif
                                @if($k->pic_industri)
                                <p><span class="text-xs text-gray-500">Industri:</span> **{{ $k->pic_industri }}**</p>
                                @endif
                                @if(!$k->pic_sekolah && !$k->pic_industri)
                                <p class="text-gray-500 italic">-</p>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2 items-start" x-data="{ open: false }">
                                @php
                                    $status_map = ['aktif' => 'success', 'proposal' => 'warning', 'negosiasi' => 'warning', 'selesai' => 'info', 'batal' => 'danger', 'draft' => 'gray'];
                                    $status_color = $status_map[$k->status] ?? 'gray';
                                @endphp

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($status_color == 'success') bg-green-100 text-green-800
                                    @elseif($status_color == 'warning') bg-yellow-100 text-yellow-800
                                    @elseif($status_color == 'info') bg-blue-100 text-blue-800
                                    @elseif($status_color == 'danger') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($k->status) }}
                                </span>

                                <div class="relative">
                                    <button @click="open = !open" type="button" class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center transition duration-150 focus:outline-none">
                                        Ubah Status
                                        <svg class="w-3 h-3 ml-1 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         @click.away="open = false"
                                         class="absolute z-20 mt-2 w-32 origin-top-right bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none right-0 lg:left-0">
                                        <form action="{{ route('admin.kerjasama.status', $k->id) }}" method="POST" class="py-1">
                                            @csrf
                                            @method('PUT') {{-- Assuming a PUT or PATCH method for status update --}}
                                            @foreach(['draft', 'proposal', 'negosiasi', 'aktif', 'selesai', 'batal'] as $status_option)
                                            <button type="submit" name="status" value="{{ $status_option }}"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-100
                                                        @if($k->status == $status_option) bg-indigo-50 text-indigo-600 font-semibold @endif">
                                                {{ ucfirst($status_option) }}
                                            </button>
                                            @endforeach
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('admin.kerjasama.show', $k->id) }}"
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-50 transition duration-150"
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.kerjasama.edit', $k->id) }}"
                                   class="text-yellow-600 hover:text-yellow-800 p-1 rounded-full hover:bg-yellow-50 transition duration-150"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                @if($k->dokumen_mou)
                                <a href="{{ Storage::url($k->dokumen_mou) }}"
                                   target="_blank"
                                   class="text-green-600 hover:text-green-800 p-1 rounded-full hover:bg-green-50 transition duration-150"
                                   title="Lihat Dokumen MoU">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </a>
                                @endif
                                <form action="{{ route('admin.kerjasama.destroy', $k->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus kerjasama ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-50 transition duration-150"
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10l4-2m-4 2l-4-2m0 0v10l4 2m4-2v10l-4 2"/>
                            </svg>
                            <p class="text-lg font-semibold">Belum ada data kerjasama yang ditemukan</p>
                            <p class="text-sm mt-1">Gunakan tombol **Tambah Kerjasama Baru** untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kerjasama->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $kerjasama->links('pagination::tailwind') }} {{-- Menggunakan tailwind pagination view --}}
        </div>
        @endif
    </div>
</div>
@endsection