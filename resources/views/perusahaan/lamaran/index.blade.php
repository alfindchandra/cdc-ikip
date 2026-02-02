@extends('layouts.app')

@section('title', 'Lamaran Masuk')
@section('page-title', 'Lamaran Masuk')

@section('content')
<div class="space-y-8">
    <!-- Filter Section (Responsive Card) -->
    <div class="bg-white p-6 rounded-2xl shadow-xl transition hover:shadow-2xl">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Lamaran</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-4">
                <label for="status" class="sr-only">Pilih Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out py-2 px-3">
                    <option value="">Semua Status Lamaran</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="dilihat" {{ request('status') == 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-[1.02]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Section (More Visual) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @php
            $stats = [
                'Dikirim' => ['status' => 'dikirim', 'count' => $lamaran->where('status', 'dikirim')->count(), 'color' => 'yellow'],
                'Diproses' => ['status' => 'diproses', 'count' => $lamaran->where('status', 'diproses')->count(), 'color' => 'blue'],
                'Diterima' => ['status' => 'diterima', 'count' => $lamaran->where('status', 'diterima')->count(), 'color' => 'green'],
                'Ditolak' => ['status' => 'ditolak', 'count' => $lamaran->where('status', 'ditolak')->count(), 'color' => 'red'],
            ];
        @endphp

        @foreach($stats as $title => $data)
            <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-{{ $data['color'] }}-500 transition duration-300 hover:shadow-xl hover:translate-y-[-2px]">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">{{ $title }}</p>
                    <p class="text-4xl font-extrabold text-{{ $data['color'] }}-600">{{ $data['count'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Lamaran List (Modern Table Card) -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelamar & Studi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lowongan & Posisi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($lamaran as $l)
                    <tr class="hover:bg-indigo-50/50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $l->tanggal_melamar->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <!-- Modern Avatar -->
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg flex-shrink-0 border-2 border-indigo-200">
                                    {{ substr($l->mahasiswa->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 truncate max-w-xs">{{ $l->mahasiswa->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $l->mahasiswa->program_studi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-gray-800">{{ $l->lowongan->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $l->lowongan->posisi }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = strtolower($l->status);
                                $badge_class = '';
                                switch ($status) {
                                    case 'dikirim': $badge_class = 'bg-yellow-100 text-yellow-800 border-yellow-300'; break;
                                    case 'dilihat':
                                    case 'diproses': $badge_class = 'bg-blue-100 text-blue-800 border-blue-300'; break;
                                    case 'diterima': $badge_class = 'bg-green-100 text-green-800 border-green-300'; break;
                                    case 'ditolak': $badge_class = 'bg-red-100 text-red-800 border-red-300'; break;
                                    default: $badge_class = 'bg-gray-100 text-gray-800 border-gray-300'; break;
                                }
                            @endphp
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium border {{ $badge_class }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('perusahaan.lamaran.show', $l->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 ease-in-out flex items-center space-x-1">
                                <span>Lihat Detail</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500 bg-gray-50/50">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada lamaran masuk</p>
                            <p class="text-sm text-gray-400">Semua filter sudah diterapkan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lamaran->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{-- Menggunakan blade pagination links bawaan Laravel --}}
            {{ $lamaran->links() }}
        </div>
        @endif
    </div>
</div>
@endsection