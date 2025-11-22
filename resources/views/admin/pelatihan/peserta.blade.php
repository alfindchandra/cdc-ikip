@extends('layouts.app')

@section('title', 'Kelola Peserta')
@section('page-title', 'Kelola Peserta Pelatihan')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b pb-4 border-gray-200">
        <div class="space-y-1">
            <a href="{{ route('admin.pelatihan.show', $pelatihan->id) }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Detail Pelatihan
            </a>
            <h2 class="text-3xl font-extrabold text-gray-900">{{ $pelatihan->judul }}</h2>
            <p class="text-base text-gray-600">Kelola status pendaftaran, kehadiran, dan nilai akhir peserta.</p>
        </div>
    </div>

    ---

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Pendaftar', 'count' => $pelatihan->peserta->count(), 'color' => 'indigo'],
                ['label' => 'Menunggu', 'count' => $pelatihan->peserta->where('pivot.status_pendaftaran', 'daftar')->count(), 'color' => 'yellow'],
                ['label' => 'Diterima', 'count' => $pelatihan->peserta->where('pivot.status_pendaftaran', 'diterima')->count(), 'color' => 'green'],
                ['label' => 'Ditolak', 'count' => $pelatihan->peserta->where('pivot.status_pendaftaran', 'ditolak')->count(), 'color' => 'red'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white shadow-lg rounded-xl p-5 border border-gray-100 hover:shadow-xl transition duration-300">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                <p class="text-3xl font-extrabold text-{{ $stat['color'] }}-600 mt-1">{{ $stat['count'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    ---

    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas / Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pelatihan->peserta as $siswa)
                    <tr class="hover:bg-indigo-50/50 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    {{ substr($siswa->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $siswa->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">NIS: {{ $siswa->nis }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $siswa->kelas }} - {{ $siswa->jurusan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status_color = match($siswa->pivot->status_pendaftaran) {
                                    'diterima' => 'green',
                                    'ditolak' => 'red',
                                    default => 'yellow',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $status_color }}-100 text-{{ $status_color }}-800">
                                {{ ucfirst($siswa->pivot->status_pendaftaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($siswa->pivot->status_kehadiran)
                            @php
                                $kehadiran_color = $siswa->pivot->status_kehadiran == 'hadir' ? 'green' : ($siswa->pivot->status_kehadiran == 'izin' ? 'blue' : 'red');
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $kehadiran_color }}-100 text-{{ $kehadiran_color }}-800">
                                {{ ucfirst(str_replace('_', ' ', $siswa->pivot->status_kehadiran)) }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Belum dicatat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-900">
                            @if($siswa->pivot->nilai)
                            <span class="{{ $siswa->pivot->nilai >= 70 ? 'text-green-600' : 'text-red-500' }}">{{ $siswa->pivot->nilai }}</span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button onclick="document.getElementById('modal-manage-{{ $siswa->id }}').classList.remove('hidden')" 
                                    class="text-indigo-600 hover:text-indigo-800 p-2 rounded-full hover:bg-indigo-50 transition duration-150"
                                    title="Kelola Status & Nilai">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                        </td>
                    </tr>

                    <div id="modal-manage-{{ $siswa->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white transition-all duration-300">
                            <form action="{{ route('admin.pelatihan.peserta.status', $siswa->pivot->id) }}" method="POST">
                                @csrf
                                @method('PUT') {{-- Menggunakan PUT untuk update data pivot --}}

                                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                    <h3 class="text-xl font-bold text-gray-900">Kelola Peserta</h3>
                                    <button type="button" onclick="document.getElementById('modal-manage-{{ $siswa->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="px-6 py-6 space-y-5">
                                    <p class="text-lg font-semibold text-indigo-700">{{ $siswa->user->name }}</p>
                                    
                                    <div>
                                        <label for="status_pendaftaran_{{ $siswa->id }}" class="block text-sm font-medium text-gray-700 mb-1">Status Pendaftaran</label>
                                        <select id="status_pendaftaran_{{ $siswa->id }}" name="status_pendaftaran" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                            <option value="daftar" {{ $siswa->pivot->status_pendaftaran == 'daftar' ? 'selected' : '' }}>Menunggu (Daftar)</option>
                                            <option value="diterima" {{ $siswa->pivot->status_pendaftaran == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="ditolak" {{ $siswa->pivot->status_pendaftaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="status_kehadiran_{{ $siswa->id }}" class="block text-sm font-medium text-gray-700 mb-1">Kehadiran</label>
                                        <select id="status_kehadiran_{{ $siswa->id }}" name="status_kehadiran" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                                            <option value="" {{ $siswa->pivot->status_kehadiran == null ? 'selected' : '' }}>-- Belum diisi --</option>
                                            <option value="hadir" {{ $siswa->pivot->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="tidak_hadir" {{ $siswa->pivot->status_kehadiran == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                            <option value="izin" {{ $siswa->pivot->status_kehadiran == 'izin' ? 'selected' : '' }}>Izin</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="nilai_{{ $siswa->id }}" class="block text-sm font-medium text-gray-700 mb-1">Nilai (0-100)</label>
                                        <input type="number" id="nilai_{{ $siswa->id }}" name="nilai" value="{{ $siswa->pivot->nilai }}"
                                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" 
                                               min="0" max="100" step="0.01" placeholder="Masukkan nilai">
                                    </div>
                                </div>
                                
                                <div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3 bg-gray-50 rounded-b-xl">
                                    <button type="button" onclick="document.getElementById('modal-manage-{{ $siswa->id }}').classList.add('hidden')" 
                                            class="btn border border-gray-300 text-gray-700 hover:bg-gray-200 py-2 px-4 rounded-lg transition duration-150">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-medium">Belum ada peserta terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.modal-overlay {
    display: none; 
}
.modal-content {

    margin: 10% auto; 
}
</style>
@endpush