@extends('layouts.app')

@section('title', 'Detail PKL')

@section('content')

<!-- Define the status color mapping in a Blade block for cleaner use -->
@php
    $statusClasses = [
        'pengajuan' => ['bg-yellow-500', 'Pengajuan', 'fa-clock'],
        'diterima' => ['bg-blue-600', 'Diterima', 'fa-check-circle'],
        'berlangsung' => ['bg-indigo-600', 'Berlangsung', 'fa-sync-alt'],
        'selesai' => ['bg-green-600', 'Selesai', 'fa-trophy'],
        'ditolak' => ['bg-red-600', 'Ditolak', 'fa-times-circle'],
    ];
    $currentStatus = $statusClasses[$pkl->status] ?? $statusClasses['ditolak'];
    $statusColor = $currentStatus[0];
    $statusLabel = $currentStatus[1];
    $statusIcon = $currentStatus[2];
@endphp

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header & Aksi -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="fas fa-briefcase text-blue-600 mr-3"></i> Detail PKL
        </h1>
        <a href="{{ route('admin.pkl.index') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Status Badge Card (Menonjol) -->
    <div class="mb-8 p-5 rounded-xl shadow-lg border-l-4 {{ $statusColor }} bg-white">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-700">Status PKL Saat Ini:</h2>
            <div class="flex items-center text-lg font-bold text-white px-4 py-2 rounded-full {{ $statusColor }} shadow-md">
                <i class="fas {{ $statusIcon }} mr-2"></i> {{ $statusLabel }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Data Mahasiswa -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden h-full">
            <div class="bg-blue-600 p-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i> Data Mahasiswa
                </h2>
            </div>
            <div class="p-6">
                <dl class="space-y-3 text-sm">
                    @php
                        $mahasiswaData = [
                            'Nama' => $pkl->mahasiswa->user->name,
                            'NIM' => $pkl->mahasiswa->nim,
                            'Fakultas' => $pkl->mahasiswa->fakultas->nama ?? '-',
                            'Program Studi' => $pkl->mahasiswa->programStudi->nama ?? '-',
                            'No. Telp' => $pkl->mahasiswa->no_telp,
                            'Email' => $pkl->mahasiswa->user->email,
                        ];
                    @endphp

                    @foreach($mahasiswaData as $label => $value)
                        <div class="flex items-start">
                            <dt class="font-medium text-gray-500 w-1/3">{{ $label }}</dt>
                            <dd class="font-semibold text-gray-900 w-2/3">: {{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <!-- Data Perusahaan -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden h-full">
            <div class="bg-green-600 p-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-building mr-2"></i> Data Perusahaan
                </h2>
            </div>
            <div class="p-6">
                <dl class="space-y-3 text-sm">
                    @php
                        $perusahaanData = [
                            'Nama Perusahaan' => $pkl->perusahaan->nama_perusahaan,
                            'Bidang Usaha' => $pkl->perusahaan->bidang_usaha,
                            'Alamat' => $pkl->perusahaan->alamat,
                            'Kota' => $pkl->perusahaan->kota,
                            'No. Telp' => $pkl->perusahaan->no_telp,
                            'Email' => $pkl->perusahaan->email,
                        ];
                    @endphp

                    @foreach($perusahaanData as $label => $value)
                        <div class="flex items-start">
                            <dt class="font-medium text-gray-500 w-1/3">{{ $label }}</dt>
                            <dd class="font-semibold text-gray-900 w-2/3">: {{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </div>

    <!-- Detail PKL -->
    <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-8">
        <div class="bg-indigo-600 p-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Informasi Penugasan
            </h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            
            <!-- Kolom Detail Kiri -->
            <div class="space-y-3">
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Posisi</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ $pkl->posisi ?? '-' }}</dd>
                </div>
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Divisi</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ $pkl->divisi ?? '-' }}</dd>
                </div>
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Tanggal Mulai</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->format('d M Y') }}</dd>
                </div>
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Tanggal Selesai</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ \Carbon\Carbon::parse($pkl->tanggal_selesai)->format('d M Y') }}</dd>
                </div>
            </div>

            <!-- Kolom Detail Kanan -->
            <div class="space-y-3">
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Pembimbing Industri</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ $pkl->pembimbing_industri ?? '-' }}</dd>
                </div>
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Nilai Akhir</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">
                        : 
                        @if($pkl->nilai_akhir)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-green-100 text-green-800">{{ $pkl->nilai_akhir }}</span>
                        @else
                            <span class="text-gray-500">Belum dinilai</span>
                        @endif
                    </dd>
                </div>
                <div class="flex items-start">
                    <dt class="font-medium text-gray-500 w-1/3">Catatan</dt>
                    <dd class="font-semibold text-gray-900 w-2/3">: {{ $pkl->catatan ?? '-' }}</dd>
                </div>
            </div>
            
            <!-- Download Laporan -->
            @if($pkl->laporan_pkl)
            <div class="mt-4 md:col-span-2 border-t pt-4">
                <strong class="text-gray-700 block mb-2">Laporan PKL:</strong>
                <a href="{{ Storage::url($pkl->laporan_pkl) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <i class="fas fa-download mr-2"></i> Download Laporan
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Form Update Status & Nilai (Menggunakan Alpine.js untuk Toggle) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8" x-data="{ showUpdate: true, showNilai: true }">
        
        <!-- Form Update Status PKL -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 bg-gray-100 border-b flex justify-between items-center cursor-pointer" @click="showUpdate = !showUpdate">
                <h5 class="text-lg font-semibold text-gray-700">Update Status PKL</h5>
                <i class="fas" :class="{'fa-chevron-up': showUpdate, 'fa-chevron-down': !showUpdate}"></i>
            </div>
            <div class="p-6" x-show="showUpdate" x-collapse>
                <!-- Asumsi action form ini ke route update status -->
                <form action="" method="POST"> 
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg shadow-sm" required>
                            <option value="pengajuan" {{ $pkl->status == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                            <option value="diterima" {{ $pkl->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak" {{ $pkl->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="berlangsung" {{ $pkl->status == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ $pkl->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Form Input Nilai PKL -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 bg-gray-100 border-b flex justify-between items-center cursor-pointer" @click="showNilai = !showNilai">
                <h5 class="text-lg font-semibold text-gray-700">Input Nilai PKL</h5>
                <i class="fas" :class="{'fa-chevron-up': showNilai, 'fa-chevron-down': !showNilai}"></i>
            </div>
            <div class="p-6" x-show="showNilai" x-collapse>
                <!-- Asumsi action form ini ke route update nilai -->
                <form action="" method="POST"> 
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="nilai_akhir" class="block text-sm font-medium text-gray-700 mb-1">Nilai Akhir (0-100)</label>
                        <input type="number" name="nilai_akhir" id="nilai_akhir" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg shadow-sm" min="0" max="100" step="0.01" value="{{ $pkl->nilai_akhir }}" required>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                        <i class="fas fa-check-circle mr-2"></i> Simpan Nilai
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Jurnal PKL -->
    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="p-4 bg-gray-100 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700 flex items-center">
                <i class="fas fa-book mr-2"></i> Jurnal PKL (5 Terbaru)
            </h2>
            <a href="" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-500 hover:bg-blue-600 transition duration-150 ease-in-out">
                Lihat Semua Jurnal
            </a>
        </div>
        <div class="p-6">
            @if(isset($pkl->jurnalPkl) && $pkl->jurnalPkl->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pkl->jurnalPkl->take(5) as $index => $jurnal)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d M Y') }}</td>
                            <td class="px-6 py-4 max-w-xs text-sm text-gray-700 truncate">{{ Str::limit($jurnal->kegiatan, 100) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $validation = [
                                        'disetujui' => ['bg-green-100', 'text-green-800', 'Disetujui'],
                                        'ditolak' => ['bg-red-100', 'text-red-800', 'Ditolak'],
                                    ][$jurnal->status_validasi] ?? ['bg-yellow-100', 'text-yellow-800', 'Pending'];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $validation[0] }} {{ $validation[1] }}">
                                    {{ $validation[2] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if($jurnal->foto)
                                    <a href="{{ Storage::url($jurnal->foto) }}" target="_blank" class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-image text-xs"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-4 text-center bg-blue-50 border border-blue-200 rounded-lg text-blue-700">
                <i class="fas fa-info-circle mr-2"></i> Belum ada jurnal PKL yang ditambahkan.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection