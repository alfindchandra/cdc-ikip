@extends('layouts.app')

@section('title', 'Peserta Pelatihan')
@section('page-title', 'Peserta Pelatihan: ' . $pelatihan->judul)

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $pelatihan->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Detail Pelatihan
    </a>

    <!-- Info Pelatihan -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $pelatihan->judul }}</h2>
                    <p class="text-gray-600">{{ $pelatihan->tanggal_mulai->format('d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Peserta</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $pelatihan->peserta->count() }}</p>
                    @if($pelatihan->kuota)
                    <p class="text-sm text-gray-500">dari {{ $pelatihan->kuota }} kuota</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $pelatihan->peserta->where('pivot.status_pendaftaran', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Diterima</p>
                        <p class="text-2xl font-bold text-green-600">
                           {{ $pelatihan->peserta->where('pivot.status_pendaftaran', 'diterima')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ $pelatihan->peserta->where('pivot.status_pendaftaran', 'ditolak')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Sudah Dinilai</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $pelatihan->peserta->whereNotNull('pivot.nilai')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Peserta -->
    <div class="card">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sertifikat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pelatihan->peserta as $mahasiswa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                        {{ substr($mahasiswa->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $mahasiswa->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $mahasiswa->nim }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $mahasiswa->pivot->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge 
                                    @if($mahasiswa->pivot->status_pendaftaran == 'diterima') badge-success
                                    @elseif($mahasiswa->pivot->status_pendaftaran == 'ditolak') badge-danger
                                    @else badge-warning
                                    @endif">
                                    {{ ucfirst($mahasiswa->pivot->status_pendaftaran) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($mahasiswa->pivot->nilai)
                                <span class="text-lg font-bold text-green-600">{{ $mahasiswa->pivot->nilai }}</span>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($mahasiswa->pivot->sertifikat)
                                <a href="{{ Storage::url($mahasiswa->pivot->sertifikat) }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-700 text-sm">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                @else
                                <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="document.getElementById('modal-manage-{{ $mahasiswa->id }}').classList.remove('hidden')" 
                                        class="text-blue-600 hover:text-blue-700 font-medium">
                                    Kelola
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Kelola Peserta -->
                        <div id="modal-manage-{{ $mahasiswa->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white" onclick="event.stopPropagation()">
                                <form action="{{ route('admin.pelatihan.peserta.status', [$pelatihan->id, $mahasiswa->id]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="text-xl font-bold text-gray-900">Kelola Peserta</h3>
                                        <button type="button" onclick="document.getElementById('modal-manage-{{ $mahasiswa->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="px-6 py-6 space-y-5">
                                        <p class="text-lg font-semibold text-indigo-700">{{ $mahasiswa->user->name }}</p>

                                        <div>
                                            <label class="form-label">Status Pendaftaran</label>
                                            <select name="status_pendaftaran" class="form-select">
                                                <option value="pending" {{ $mahasiswa->pivot->status_pendaftaran == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="diterima" {{ $mahasiswa->pivot->status_pendaftaran == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                                <option value="ditolak" {{ $mahasiswa->pivot->status_pendaftaran == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="form-label">Nilai (0-100)</label>
                                            <input type="number" 
                                                   name="nilai" 
                                                   min="0" 
                                                   max="100" 
                                                   value="{{ $mahasiswa->pivot->nilai }}"
                                                   class="form-input" 
                                                   placeholder="Masukkan nilai">
                                        </div>

                                        <div>
                                            <label class="form-label">Upload Sertifikat</label>
                                            <input type="file" 
                                                   name="sertifikat" 
                                                   accept=".pdf,.jpg,.jpeg,.png"
                                                   class="form-input">
                                            @if($mahasiswa->pivot->sertifikat)
                                            <p class="text-xs text-gray-500 mt-1">
                                                Sertifikat sudah ada. Upload file baru untuk mengganti.
                                            </p>
                                            @else
                                            <p class="text-xs text-gray-500 mt-1">Format: PDF/JPG/PNG, maksimal 2MB</p>
                                            @endif
                                        </div>

                                        <div>
                                            <label class="form-label">Catatan</label>
                                            <textarea name="catatan" 
                                                      rows="3" 
                                                      class="form-textarea"
                                                      placeholder="Catatan untuk peserta...">{{ $mahasiswa->pivot->catatan }}</textarea>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3">
                                        <button type="button" 
                                                onclick="document.getElementById('modal-manage-{{ $mahasiswa->id }}').classList.add('hidden')" 
                                                class="btn btn-outline">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p>Belum ada peserta terdaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
@endsection