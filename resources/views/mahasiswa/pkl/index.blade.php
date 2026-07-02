@extends('layouts.app')

@section('title', 'PKL Saya')
@section('page-title', 'Praktik Kerja Lapangan (PKL)')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-8">
        @php
            // Memastikan data PKL aktif sudah tersedia
            $pklAktif = auth()->user()->mahasiswa->pkl()
                ->whereIn('status', ['berlangsung', 'diterima', 'pengajuan'])
                ->first();
            
            // Mapping warna status untuk Tailwind
            $statusMap = [
                'pengajuan' => ['bg-yellow-100 text-yellow-800', 'border-yellow-500'],
                'diterima' => ['bg-blue-100 text-blue-800', 'border-blue-500'],
                'berlangsung' => ['bg-green-100 text-green-800', 'border-green-500'],
                'selesai' => ['bg-gray-100 text-gray-600', 'border-gray-500'],
                'ditolak' => ['bg-red-100 text-red-800', 'border-red-500'],
            ];
        @endphp

        @if($pklAktif)
        <div class="bg-white rounded-xl shadow-xl overflow-hidden p-6 border-l-8 {{ $statusMap[$pklAktif->status][1] ?? 'border-gray-300' }}">
            <div class="flex flex-col sm:flex-row items-start justify-between mb-4">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mb-2 {{ $statusMap[$pklAktif->status][0] }}">
                        <i class="fas fa-briefcase mr-2"></i> PKL {{ ucfirst($pklAktif->status) }}
                    </span>
                    <h2 class="text-2xl font-extrabold text-gray-900">{{ $pklAktif->perusahaan->nama_perusahaan }}</h2>
                    <p class="text-gray-600 mt-1 text-lg font-medium">{{ $pklAktif->posisi ?? 'Peserta PKL' }}</p>
                </div>
                <span class="text-sm font-semibold mt-2 sm:mt-0 px-3 py-1 rounded-full {{ $statusMap[$pklAktif->status][0] }}">
                    {{ ucfirst($pklAktif->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-b py-4 mb-6 text-sm">
                <div>
                    <p class="text-gray-500">Periode PKL</p>
                    <p class="font-semibold text-gray-900 mt-0.5">
                        {{ $pklAktif->tanggal_mulai->format('d M Y') }} - {{ $pklAktif->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Durasi</p>
                    <p class="font-semibold text-gray-900 mt-0.5">
                        {{ $pklAktif->tanggal_mulai->diffInMonths($pklAktif->tanggal_selesai) }} Bulan
                    </p>
                </div>
                <div class="hidden md:block">
                    <p class="text-gray-500">Pembimbing Lapangan</p>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $pklAktif->pembimbing_industri ?? '-' }}</p>
                </div>
                <div class="hidden md:block">
                    <p class="text-gray-500">Nilai Akhir</p>
                    <p class="font-semibold text-gray-900 mt-0.5">
                        @if($pklAktif->nilai_akhir)
                            <span class="text-green-600">{{ $pklAktif->nilai_akhir }}</span>
                        @else
                            <span class="text-yellow-600">Belum Dinilai</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('mahasiswa.pkl.show', $pklAktif->id) }}" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail & Jurnal
                </a>
                
                @if($pklAktif->status === 'berlangsung')
                <button onclick="document.getElementById('modalUploadLaporan').classList.remove('hidden')" class="inline-flex items-center justify-center px-5 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Laporan PKL
                </button>
                @endif
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-xl border-l-8 border-blue-500 p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada PKL Aktif</h3>
            <p class="text-gray-600 mb-6">Anda dapat mengajukan permohonan PKL ke perusahaan mitra yang tersedia.</p>
            <button onclick="document.getElementById('modalDaftarPKL').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150 ease-in-out">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Daftar PKL
            </button>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-xl font-semibold text-gray-900">Riwayat PKL</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse(auth()->user()->mahasiswa->pkl()->latest()->get() as $pkl)
                    @php
                        $historyStatus = $statusMap[$pkl->status] ?? $statusMap['selesai']; // Gunakan 'selesai' sebagai default jika status tidak terdefinisi
                    @endphp
                    <div class="border-l-4 {{ $historyStatus[1] }} pl-4 py-3 bg-white hover:bg-gray-50 transition duration-150 ease-in-out rounded-md">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $pkl->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i> {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}
                                </p>
                                @if($pkl->nilai_akhir)
                                <p class="text-sm text-green-600 font-bold mt-2">
                                    Nilai: {{ $pkl->nilai_akhir }}
                                </p>
                                @endif
                            </div>
                            <div class="text-right flex flex-col items-end">
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold {{ $historyStatus[0] }} mb-2">
                                    {{ ucfirst($pkl->status) }}
                                </span>
                                <a href="{{ route('mahasiswa.pkl.show', $pkl->id) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-1">
                                    Lihat Detail →
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada riwayat PKL</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalDaftarPKL" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-5 border w-full sm:max-w-xl shadow-lg rounded-xl bg-white transition-all duration-300">
        <form action="{{ route('mahasiswa.pkl.daftar') }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Daftar PKL Baru</h3>
                    <button type="button" onclick="document.getElementById('modalDaftarPKL').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Perusahaan <span class="text-red-500">*</span></label>
                    <select name="perusahaan_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach(\App\Models\Perusahaan::where('status_kerjasama', 'aktif')->get() as $perusahaan)
                        <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi yang Diinginkan</label>
                    <input type="text" name="posisi" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Web Developer">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis alasan atau catatan tambahan..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalDaftarPKL').classList.add('hidden')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@if($pklAktif)
<div id="modalUploadLaporan" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="relative top-20 mx-auto p-5 border w-full sm:max-w-xl shadow-lg rounded-xl bg-white transition-all duration-300">
        <form action="{{ route('mahasiswa.pkl.laporan', $pklAktif->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Upload Laporan PKL</h3>
                    <button type="button" onclick="document.getElementById('modalUploadLaporan').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File Laporan PKL (PDF) <span class="text-red-500">*</span></label>
                    <input type="file" name="laporan_pkl" accept=".pdf" class="w-full block text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 10MB</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold">Perhatian! Pastikan laporan PKL Anda sudah sesuai format:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Cover dengan identitas lengkap</li>
                                <li>Lembar pengesahan</li>
                                <li>Isi laporan kegiatan PKL</li>
                                <li>Dokumentasi kegiatan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalUploadLaporan').classList.add('hidden')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                    <i class="fas fa-cloud-upload-alt mr-2"></i> Upload Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush