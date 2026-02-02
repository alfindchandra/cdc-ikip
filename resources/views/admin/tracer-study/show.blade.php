@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="fas fa-user-graduate text-blue-600 mr-3"></i> Detail Tracer Study
        </h1>
        <div class="flex space-x-3 mt-4 md:mt-0">
            <a href="{{ route('admin.tracer-study.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <a href="{{ route('admin.tracer-study.edit', $tracerStudy) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        
        <div class="w-full lg:w-1/3 px-4 mb-8">
            
            <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-6">
                <div class="bg-blue-600 p-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-user mr-2"></i> Data Alumni
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        @if($tracerStudy->mahasiswa->user->avatar)
                            <img src="{{ asset('storage/' . $tracerStudy->mahasiswa->user->avatar) }}" 
                                class="rounded-full mx-auto w-32 h-32 object-cover border-4 border-blue-200" alt="Avatar">
                        @else
                            <div class="rounded-full bg-gray-300 mx-auto flex items-center justify-center w-32 h-32">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>

                    <dl class="space-y-3 text-sm">
                        @php
                            $alumniData = [
                                'Nama' => $tracerStudy->mahasiswa->user->name,
                                'NIM' => $tracerStudy->mahasiswa->nim,
                                'Fakultas' => $tracerStudy->mahasiswa->fakultas->nama ?? '-',
                                'Program Studi' => $tracerStudy->mahasiswa->programStudi->nama ?? '-',
                                'Tahun Masuk' => $tracerStudy->mahasiswa->tahun_masuk,
                                'Tahun Lulus' => $tracerStudy->mahasiswa->tahun_lulus ?? '-',
                                'Tanggal Isi' => $tracerStudy->tanggal_isi ? $tracerStudy->tanggal_isi->format('d F Y') : '-',
                            ];
                        @endphp

                        @foreach($alumniData as $label => $value)
                            <div class="flex justify-between items-start border-b border-gray-100 pb-2">
                                <dt class="font-semibold text-gray-700">{{ $label }}</dt>
                                <dd class="text-gray-900 text-right max-w-[50%]">
                                    @if($label === 'Tanggal Isi')
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $value }}
                                        </span>
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-indigo-600 p-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-address-book mr-2"></i> Kontak Terkini
                    </h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-500">Email Saat Ini</p>
                        <p class="font-bold text-gray-900">{{ $tracerStudy->email_saat_ini ?? '-' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-xs font-medium text-gray-500">Nomor Telepon</p>
                        <p class="font-bold text-gray-900">{{ $tracerStudy->no_telp_saat_ini ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">LinkedIn</p>
                        @if($tracerStudy->linkedin)
                            <a href="{{ $tracerStudy->linkedin }}" target="_blank" class="mt-1 inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fab fa-linkedin mr-1"></i> Lihat Profil
                            </a>
                        @else
                            <p class="text-gray-500">-</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/3 px-4 mb-8" x-data="{ activeTab: 'status' }">

            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 p-4">
                        <button @click="activeTab = 'status'" :class="{'border-blue-500 text-blue-600': activeTab === 'status', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'status'}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center">
                            <i class="fas fa-briefcase mr-2"></i> Status & Informasi
                        </button>
                        <button @click="activeTab = 'feedback'" :class="{'border-blue-500 text-blue-600': activeTab === 'feedback', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'feedback'}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center">
                            <i class="fas fa-star mr-2"></i> Kepuasan & Feedback
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <div x-show="activeTab === 'status'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400 mb-6">
                            <h4 class="text-xl font-bold text-blue-800 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Status Terkini: <span class="ml-2 px-3 py-1 bg-blue-600 text-white rounded-full text-sm">{{ $tracerStudy->status_pekerjaan_label }}</span>
                            </h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            @if($tracerStudy->status_pekerjaan == 'bekerja')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Pekerjaan</h6>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Nama Perusahaan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->nama_perusahaan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Posisi/Jabatan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->posisi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Bidang Pekerjaan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->bidang_pekerjaan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Jenis Perusahaan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->jenis_perusahaan_label }}</p>
                                </div>
                                <div class="col-span-full">
                                    <p class="text-xs font-medium text-gray-500">Alamat Perusahaan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->alamat_perusahaan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Penghasilan per Bulan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->penghasilan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Relevansi dengan Jurusan</p>
                                    @php
                                        $relevansiClass = [
                                            'sangat_relevan' => 'bg-green-500',
                                            'relevan' => 'bg-blue-500',
                                            'cukup_relevan' => 'bg-indigo-500',
                                            'tidak_relevan' => 'bg-red-500',
                                        ][$tracerStudy->relevansi_pekerjaan] ?? 'bg-gray-400';
                                    @endphp
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium text-white {{ $relevansiClass }}">
                                            {{ $tracerStudy->relevansi_pekerjaan_label }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Cara Mendapat Pekerjaan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->cara_mendapat_pekerjaan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Waktu Tunggu Kerja</p>
                                    <p class="font-bold text-gray-900">
                                        {{ $tracerStudy->waktu_tunggu_kerja ?? '-' }} 
                                        @if($tracerStudy->waktu_tunggu_kerja) Bulan @endif
                                    </p>
                                </div>
                            @elseif($tracerStudy->status_pekerjaan == 'wirausaha')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Usaha</h6>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Nama Usaha</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->nama_usaha ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Bidang Usaha</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->bidang_usaha ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Jumlah Karyawan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->jumlah_karyawan ?? '-' }} Orang</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Omzet per Bulan</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->omzet_usaha ?? '-' }}</p>
                                </div>
                            @elseif($tracerStudy->status_pekerjaan == 'melanjutkan_studi')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informasi Studi Lanjutan</h6>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Nama Institusi</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->nama_institusi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Jenjang Studi</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->jenjang_studi_label }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Jurusan/Program Studi</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->jurusan_studi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Sumber Biaya</p>
                                    <p class="font-bold text-gray-900">{{ $tracerStudy->sumber_biaya ?? '-' }}</p>
                                </div>
                            @else
                                <div class="col-span-full text-center py-6 text-gray-500">
                                    <i class="fas fa-tag text-2xl mb-2"></i>
                                    <p>Tidak ada data informasi terkait status pekerjaan/studi saat ini.</p>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div x-show="activeTab === 'feedback'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        <h6 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center text-yellow-600">
                            <i class="fas fa-star mr-2"></i> Kepuasan & Feedback
                        </h6>
                        
                        <div class="mb-6 border-b pb-4">
                            <p class="text-xs font-medium text-gray-500 mb-1">Tingkat Kepuasan terhadap Pendidikan</p>
                            <div class="flex items-center">
                                <span class="text-4xl font-extrabold text-yellow-600 mr-4">
                                    {{ $tracerStudy->kepuasan_pendidikan ?? '?' }} / 5
                                </span>
                                <div class="text-2xl space-x-0.5">
                                    @if($tracerStudy->kepuasan_pendidikan)
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $tracerStudy->kepuasan_pendidikan ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    @else
                                        <span class="text-base text-gray-500">Belum diisi</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @php
                                $saranData = [
                                    'Saran untuk Kurikulum' => $tracerStudy->saran_kurikulum,
                                    'Saran untuk Fasilitas' => $tracerStudy->saran_fasilitas,
                                    'Saran Umum' => $tracerStudy->saran_umum,
                                ];
                            @endphp

                            @foreach($saranData as $label => $value)
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">{{ $label }}</p>
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-gray-700 text-sm italic">
                                        {{ $value ?? 'Tidak ada saran' }}
                                    </div>
                                </div>
                            @endforeach

                            @if($tracerStudy->kompetensi_yang_digunakan)
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-2">Kompetensi yang Digunakan</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(is_array($tracerStudy->kompetensi_yang_digunakan) ? $tracerStudy->kompetensi_yang_digunakan : explode(', ', $tracerStudy->kompetensi_yang_digunakan) as $kompetensi)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                {{ $kompetensi }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection