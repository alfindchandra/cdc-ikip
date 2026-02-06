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
           
            <button onclick="confirmDelete()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </div>
    </div>

    <div class="flex flex-wrap -mx-4">
        
        {{-- Left Sidebar - Data Alumni --}}
        <div class="w-full lg:w-1/3 px-4 mb-8">
            
            <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-user mr-2"></i> Data Alumni
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center mb-6">
                        @if($tracerStudy->mahasiswa->user->avatar ?? false)
                            <img src="{{ asset('storage/' . $tracerStudy->mahasiswa->user->avatar) }}" 
                                class="rounded-full mx-auto w-32 h-32 object-cover border-4 border-blue-200" alt="Avatar">
                        @else
                            <div class="rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 mx-auto flex items-center justify-center w-32 h-32 border-4 border-blue-200">
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
                                'Tanggal Isi' => $tracerStudy->tanggal_isi ? $tracerStudy->tanggal_isi->format('d F Y, H:i') : '-',
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

            {{-- Kontak Terkini --}}
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-address-book mr-2"></i> Kontak Terkini
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Email Saat Ini</p>
                        <p class="font-bold text-gray-900 break-all">{{ $tracerStudy->email_saat_ini ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Nomor Telepon</p>
                        <p class="font-bold text-gray-900">{{ $tracerStudy->no_telp_saat_ini ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">LinkedIn</p>
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

        {{-- Main Content - Detail Tabs --}}
        <div class="w-full lg:w-2/3 px-4 mb-8" x-data="{ activeTab: 'status' }">

            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                {{-- Tabs Navigation --}}
                <div class="border-b border-gray-200 bg-gray-50">
                    <nav class="-mb-px flex space-x-8 p-4 overflow-x-auto">
                        <button @click="activeTab = 'status'" :class="{'border-blue-500 text-blue-600': activeTab === 'status', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'status'}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center">
                            <i class="fas fa-briefcase mr-2"></i> Status & Pekerjaan
                        </button>
                        <button @click="activeTab = 'kompetensi'" :class="{'border-blue-500 text-blue-600': activeTab === 'kompetensi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kompetensi'}" class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out flex items-center">
                            <i class="fas fa-chart-line mr-2"></i> Kompetensi
                        </button>
                        
                    </nav>
                </div>

                <div class="p-6">
                    {{-- Tab 1: Status & Informasi --}}
                    <div x-show="activeTab === 'status'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        
                        {{-- Status Badge --}}
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-l-4 border-blue-400 mb-6">
                            <h4 class="text-xl font-bold text-blue-800 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Status Terkini: 
                                @php
                                    $statusLabels = [
                                        'bekerja' => ['label' => 'Bekerja', 'class' => 'bg-green-600'],
                                        'wirausaha' => ['label' => 'Wirausaha', 'class' => 'bg-blue-600'],
                                        'melanjutkan_studi' => ['label' => 'Melanjutkan Studi', 'class' => 'bg-purple-600'],
                                        'belum_bekerja' => ['label' => 'Belum Bekerja', 'class' => 'bg-amber-600'],
                                        'belum_memungkinkan_bekerja' => ['label' => 'Belum Memungkinkan Bekerja', 'class' => 'bg-gray-600'],
                                    ];
                                    $currentStatus = $statusLabels[$tracerStudy->status_pekerjaan] ?? ['label' => $tracerStudy->status_pekerjaan, 'class' => 'bg-gray-600'];
                                @endphp
                                <span class="ml-2 px-3 py-1 {{ $currentStatus['class'] }} text-white rounded-full text-sm">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            @if($tracerStudy->status_pekerjaan == 'bekerja')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center">
                                    <i class="fas fa-building mr-2 text-green-600"></i> Informasi Pekerjaan
                                </h6>
                                
                                @php
                                    $pekerjaanData = [
                                        'Nama Perusahaan' => $tracerStudy->nama_perusahaan ?? '-',
                                        'Posisi/Jabatan' => $tracerStudy->posisi ?? '-',
                                        'Bidang Pekerjaan' => $tracerStudy->bidang_pekerjaan ?? '-',
                                        'Jenis Perusahaan' => ucfirst($tracerStudy->jenis_perusahaan ?? '-'),
                                        'Alamat Perusahaan' => $tracerStudy->alamat_perusahaan ?? '-',
                                        'Tingkat Tempat Kerja' => $tracerStudy->tingkat_tempat_kerja ?? '-',
                                        'Penghasilan per Bulan' => $tracerStudy->penghasilan ? 'Rp ' . number_format($tracerStudy->penghasilan, 0, ',', '.') : '-',
                                        'Waktu Tunggu Kerja' => ($tracerStudy->waktu_tunggu_kerja ?? '-') . ' Bulan',
                                        'Cara Mendapat Pekerjaan' => $tracerStudy->cara_mendapat_pekerjaan ?? '-',
                                    ];
                                @endphp

                                @foreach($pekerjaanData as $label => $value)
                                    <div class="{{ in_array($label, ['Alamat Perusahaan']) ? 'col-span-full' : '' }}">
                                        <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
                                        <p class="font-bold text-gray-900 mt-1">{{ $value }}</p>
                                    </div>
                                @endforeach

                                <div>
                                    <p class="text-xs font-medium text-gray-500">Relevansi dengan Jurusan</p>
                                    @php
                                        $relevansiClass = [
                                            'sangat_relevan' => 'bg-green-500',
                                            'relevan' => 'bg-blue-500',
                                            'cukup_relevan' => 'bg-indigo-500',
                                            'kurang_erat' => 'bg-yellow-500',
                                            'tidak_relevan' => 'bg-red-500',
                                        ][$tracerStudy->relevansi_pekerjaan] ?? 'bg-gray-400';
                                        
                                        $relevansiLabel = [
                                            'sangat_relevan' => 'Sangat Relevan',
                                            'relevan' => 'Relevan',
                                            'cukup_relevan' => 'Cukup Relevan',
                                            'kurang_erat' => 'Kurang Erat',
                                            'tidak_relevan' => 'Tidak Relevan',
                                        ][$tracerStudy->relevansi_pekerjaan] ?? '-';
                                    @endphp
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium text-white {{ $relevansiClass }}">
                                            {{ $relevansiLabel }}
                                        </span>
                                    </p>
                                </div>

                            @elseif($tracerStudy->status_pekerjaan == 'wirausaha')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center">
                                    <i class="fas fa-store mr-2 text-blue-600"></i> Informasi Usaha
                                </h6>
                                
                                @php
                                    $usahaData = [
                                        'Nama Usaha' => $tracerStudy->nama_usaha ?? '-',
                                        'Bidang Usaha' => $tracerStudy->bidang_usaha ?? '-',
                                        'Jumlah Karyawan' => ($tracerStudy->jumlah_karyawan ?? '0') . ' Orang',
                                        'Omzet per Bulan' => $tracerStudy->omzet_usaha ? 'Rp ' . number_format($tracerStudy->omzet_usaha, 0, ',', '.') : '-',
                                    ];
                                @endphp

                                @foreach($usahaData as $label => $value)
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
                                        <p class="font-bold text-gray-900 mt-1">{{ $value }}</p>
                                    </div>
                                @endforeach

                            @elseif($tracerStudy->status_pekerjaan == 'melanjutkan_studi')
                                <h6 class="col-span-full text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center">
                                    <i class="fas fa-graduation-cap mr-2 text-purple-600"></i> Informasi Studi Lanjutan
                                </h6>
                                
                                @php
                                    $studiData = [
                                        'Nama Institusi' => $tracerStudy->nama_institusi ?? '-',
                                        'Jenjang Studi' => strtoupper($tracerStudy->jenjang_studi ?? '-'),
                                        'Jurusan/Program Studi' => $tracerStudy->jurusan_studi ?? '-',
                                        'Sumber Biaya' => ucfirst($tracerStudy->sumber_biaya ?? '-'),
                                    ];
                                @endphp

                                @foreach($studiData as $label => $value)
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">{{ $label }}</p>
                                        <p class="font-bold text-gray-900 mt-1">{{ $value }}</p>
                                    </div>
                                @endforeach

                            @else
                                <div class="col-span-full text-center py-6 text-gray-500">
                                    <i class="fas fa-info-circle text-4xl mb-2"></i>
                                    <p>Tidak ada data informasi detail untuk status ini.</p>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Tab 2: Kompetensi --}}
                    <div x-show="activeTab === 'kompetensi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">
                        <h6 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4 flex items-center text-indigo-600">
                            <i class="fas fa-chart-line mr-2"></i> Data Kompetensi & Pembelajaran
                        </h6>

                        @if($tracerStudy->kompetensi_yang_digunakan)
                            @php
                                $kompetensiData = is_string($tracerStudy->kompetensi_yang_digunakan) 
                                    ? json_decode($tracerStudy->kompetensi_yang_digunakan, true) 
                                    : $tracerStudy->kompetensi_yang_digunakan;
                            @endphp

                            {{-- Kompetensi Saat Lulus --}}
                            @if(isset($kompetensiData['saat_lulus']) && !empty($kompetensiData['saat_lulus']))
                                <div class="mb-6">
                                    <h6 class="text-md font-semibold text-gray-700 mb-3">Kompetensi Saat Lulus</h6>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($kompetensiData['saat_lulus'] as $key => $nilai)
                                            @php
                                                $namaKompetensi = [
                                                    'etika' => 'Etika',
                                                    'keahlian_bidang' => 'Keahlian Bidang Ilmu',
                                                    'bahasa_inggris' => 'Bahasa Inggris',
                                                    'teknologi_informasi' => 'Teknologi Informasi',
                                                    'komunikasi' => 'Komunikasi',
                                                    'kerja_sama_tim' => 'Kerja Sama Tim',
                                                    'pengembangan_diri' => 'Pengembangan Diri',
                                                ][$key] ?? ucfirst(str_replace('_', ' ', $key));
                                            @endphp
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 mb-2">{{ $namaKompetensi }}</p>
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($nilai / 5) * 100 }}%"></div>
                                                    </div>
                                                    <span class="ml-3 text-sm font-bold text-indigo-600">{{ $nilai }}/5</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Kompetensi yang Diperlukan --}}
                            @if(isset($kompetensiData['diperlukan']) && !empty($kompetensiData['diperlukan']))
                                <div class="mb-6">
                                    <h6 class="text-md font-semibold text-gray-700 mb-3">Kompetensi yang Diperlukan dalam Pekerjaan</h6>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($kompetensiData['diperlukan'] as $key => $nilai)
                                            @php
                                                $namaKompetensi = [
                                                    'etika' => 'Etika',
                                                    'keahlian_bidang' => 'Keahlian Bidang Ilmu',
                                                    'bahasa_inggris' => 'Bahasa Inggris',
                                                    'teknologi_informasi' => 'Teknologi Informasi',
                                                    'komunikasi' => 'Komunikasi',
                                                    'kerja_sama_tim' => 'Kerja Sama Tim',
                                                    'pengembangan_diri' => 'Pengembangan Diri',
                                                ][$key] ?? ucfirst(str_replace('_', ' ', $key));
                                            @endphp
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 mb-2">{{ $namaKompetensi }}</p>
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($nilai / 5) * 100 }}%"></div>
                                                    </div>
                                                    <span class="ml-3 text-sm font-bold text-green-600">{{ $nilai }}/5</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Metode Pembelajaran --}}
                            @if(isset($kompetensiData['metode_pembelajaran']) && !empty($kompetensiData['metode_pembelajaran']))
                                <div class="mb-6">
                                    <h6 class="text-md font-semibold text-gray-700 mb-3">Metode Pembelajaran</h6>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($kompetensiData['metode_pembelajaran'] as $key => $nilai)
                                            @php
                                                $namaMetode = [
                                                    'perkuliahan' => 'Perkuliahan',
                                                    'demonstrasi' => 'Demonstrasi',
                                                    'partisipasi_riset' => 'Partisipasi Riset',
                                                    'magang' => 'Magang',
                                                    'praktikum' => 'Praktikum',
                                                    'kerja_lapangan' => 'Kerja Lapangan',
                                                    'diskusi' => 'Diskusi',
                                                ][$key] ?? ucfirst(str_replace('_', ' ', $key));
                                            @endphp
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <p class="text-sm font-medium text-gray-700 mb-2">{{ $namaMetode }}</p>
                                                <div class="flex items-center">
                                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($nilai / 5) * 100 }}%"></div>
                                                    </div>
                                                    <span class="ml-3 text-sm font-bold text-purple-600">{{ $nilai }}/5</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Pencarian Kerja --}}
                            @if(isset($kompetensiData['pencarian_kerja']) && !empty($kompetensiData['pencarian_kerja']))
                                <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                                    <h6 class="text-md font-semibold text-gray-700 mb-3">Informasi Pencarian Kerja</h6>
                                    <div class="grid grid-cols-2 gap-4">
                                        @if(isset($kompetensiData['pencarian_kerja']['kapan']))
                                            <div>
                                                <p class="text-xs text-gray-600">Mulai Mencari</p>
                                                <p class="font-bold">{{ ucfirst(str_replace('_', ' ', $kompetensiData['pencarian_kerja']['kapan'])) }}</p>
                                            </div>
                                        @endif
                                        @if(isset($kompetensiData['pencarian_kerja']['jumlah_lamaran']))
                                            <div>
                                                <p class="text-xs text-gray-600">Jumlah Lamaran</p>
                                                <p class="font-bold">{{ $kompetensiData['pencarian_kerja']['jumlah_lamaran'] }}</p>
                                            </div>
                                        @endif
                                        @if(isset($kompetensiData['pencarian_kerja']['jumlah_respons']))
                                            <div>
                                                <p class="text-xs text-gray-600">Jumlah Respons</p>
                                                <p class="font-bold">{{ $kompetensiData['pencarian_kerja']['jumlah_respons'] }}</p>
                                            </div>
                                        @endif
                                        @if(isset($kompetensiData['pencarian_kerja']['jumlah_wawancara']))
                                            <div>
                                                <p class="text-xs text-gray-600">Jumlah Wawancara</p>
                                                <p class="font-bold">{{ $kompetensiData['pencarian_kerja']['jumlah_wawancara'] }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if(isset($kompetensiData['pencarian_kerja']['cara']) && !empty($kompetensiData['pencarian_kerja']['cara']))
                                        <div class="mt-4">
                                            <p class="text-xs text-gray-600 mb-2">Cara Mencari Pekerjaan</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($kompetensiData['pencarian_kerja']['cara'] as $cara)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst(str_replace('_', ' ', $cara)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        @else
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-chart-line text-4xl mb-2"></i>
                                <p>Tidak ada data kompetensi yang tersedia.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tab 3: Kepuasan & Feedback --}}
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
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 text-sm">
                                        {{ $value ?? 'Tidak ada saran' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Form --}}
<form id="delete-form" action="{{ route('admin.tracer-study.destroy', $tracerStudy) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tracer study akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush
@endsection