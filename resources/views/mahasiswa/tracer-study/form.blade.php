@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center mb-2">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Kuesioner Tracer Study
                    </h1>
                    <p class="text-indigo-100">IKIP PGRI Bojonegoro - Tahun {{ date('Y') }}</p>
                </div>
                @if(isset($tracerStudy) && $tracerStudy)
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-sm">Terakhir diisi:</p>
                        <p class="font-semibold">{{ $tracerStudy->tanggal_isi->format('d F Y') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-8" x-data="tracerStudyForm()">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-center justify-between" 
                     x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-green-700 hover:text-green-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold">Terdapat kesalahan:</p>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- reCAPTCHA Notice --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-700">Informasi Penting</p>
                        <p class="text-sm text-blue-600 mt-1">Anda perlu memverifikasi bahwa Anda bukan robot sebelum mengirim kuesioner ini.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('mahasiswa.tracer-study.store') }}" method="POST" id="tracerStudyForm">
                @csrf

                {{-- SECTION 1: STATUS ALUMNI (f8) --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">1</span>
                        Status Saat Ini
                    </h2>
                    
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <label class="block text-sm font-bold text-gray-700 mb-4">
                            Jelaskan status Anda saat ini? <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            @php
                                $statusOptions = [
                                    'bekerja' => ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Bekerja (full time/part time)', 'color' => 'green'],
                                    'belum_memungkinkan_bekerja' => ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Belum memungkinkan bekerja', 'color' => 'yellow'],
                                    'wirausaha' => ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Wiraswasta', 'color' => 'blue'],
                                    'melanjutkan_studi' => ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Melanjutkan Pendidikan', 'color' => 'purple'],
                                    'belum_bekerja' => ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'label' => 'Tidak kerja tetapi sedang mencari kerja', 'color' => 'red']
                                ];
                            @endphp

                            @foreach($statusOptions as $value => $option)
                                <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-{{ $option['color'] }}-500 hover:bg-{{ $option['color'] }}-50 transition-all group">
                                    <input type="radio" 
                                           name="status_pekerjaan" 
                                           value="{{ $value }}" 
                                           x-model="status"
                                           {{ old('status_pekerjaan', $tracerStudy->status_pekerjaan ?? '') == $value ? 'checked' : '' }}
                                           class="w-5 h-5 text-{{ $option['color'] }}-600 border-gray-300 focus:ring-{{ $option['color'] }}-500"
                                           required>
                                    <div class="ml-4 flex items-center flex-1">
                                        <svg class="w-6 h-6 text-{{ $option['color'] }}-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $option['icon'] }}"></path>
                                        </svg>
                                        <span class="text-gray-800 font-medium">{{ $option['label'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ALUMNI BEKERJA --}}
                <div x-show="status === 'bekerja'" x-transition class="mb-10 p-6 bg-green-50 rounded-2xl border-2 border-green-200">
                    <h2 class="text-2xl font-bold pb-3 mb-6 flex items-center text-green-800">
                        <span class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">2</span>
                        Informasi Pekerjaan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- f502: Waktu mendapat pekerjaan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Dalam berapa bulan Anda mendapatkan pekerjaan? (sejak mendapat nomor ijazah)
                            </label>
                            <input type="number" 
                                   name="waktu_tunggu_kerja" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="{{ old('waktu_tunggu_kerja', $tracerStudy->waktu_tunggu_kerja ?? '') }}"
                                   placeholder="Contoh: 3"
                                   min="0">
                        </div>

                        {{-- f505: Pendapatan --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Berapa rata-rata pendapatan Anda per bulan? (take home pay)
                            </label>
                            <input type="number" 
                                   name="penghasilan_nominal" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="{{ old('penghasilan_nominal', $tracerStudy->penghasilan ?? '') }}"
                                   placeholder="Contoh: 5000000 (tanpa titik/koma)">
                        </div>

                        {{-- f5a1: Provinsi --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Dimana lokasi tempat Anda bekerja? (Provinsi)
                            </label>
                            <select name="provinsi_kerja" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih Provinsi</option>
                                @php
                                    $provinsi = ['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung', 'Kepulauan Riau', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten', 'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara', 'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat'];
                                @endphp
                                @foreach($provinsi as $prov)
                                    <option value="{{ $prov }}">{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- f5a2: Kota/Kabupaten --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kota/Kabupaten
                            </label>
                            <input type="text" 
                                   name="kota_kerja" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: Bojonegoro">
                        </div>

                        {{-- f1101: Jenis perusahaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Apa jenis perusahaan/instansi tempat anda bekerja?
                            </label>
                            <select name="jenis_perusahaan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih Jenis</option>
                                <option value="pemerintah">Instansi pemerintah</option>
                                <option value="nonprofit">Organisasi nonprofit/Lembaga Swadaya Masyarakat</option>
                                <option value="swasta">Perusahaan swasta</option>
                                <option value="wirausaha">Wiraswasta/perusahaan sendiri</option>
                                <option value="bumn">BUMN/BUMD</option>
                                <option value="multilateral">Institusi/Organisasi Multilateral</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>

                        {{-- f5b: Nama perusahaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama perusahaan/kantor tempat Anda bekerja
                            </label>
                            <input type="text" 
                                   name="nama_perusahaan" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="{{ old('nama_perusahaan', $tracerStudy->nama_perusahaan ?? '') }}"
                                   placeholder="Nama perusahaan">
                        </div>

                        {{-- f5d: Tingkat tempat kerja --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tingkat tempat kerja Anda
                            </label>
                            <select name="tingkat_tempat_kerja" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih Tingkat</option>
                                <option value="lokal">Lokal/Wilayah/Wiraswasta Tidak Berbadan Hukum</option>
                                <option value="nasional">Nasional/Wiraswasta Berbadan Hukum</option>
                                <option value="multinasional">Multinasional/Internasional</option>
                            </select>
                        </div>

                        {{-- f14: Hubungan bidang studi dengan pekerjaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Seberapa erat hubungan bidang studi dengan pekerjaan Anda?
                            </label>
                            <select name="relevansi_pekerjaan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih</option>
                                <option value="sangat_relevan">Sangat Erat</option>
                                <option value="relevan">Erat</option>
                                <option value="cukup_relevan">Cukup Erat</option>
                                <option value="kurang_erat">Kurang Erat</option>
                                <option value="tidak_relevan">Tidak Sama Sekali</option>
                            </select>
                        </div>

                        {{-- f15: Tingkat pendidikan untuk pekerjaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tingkat pendidikan apa yang paling tepat/sesuai untuk pekerjaan anda saat ini?
                            </label>
                            <select name="tingkat_pendidikan_pekerjaan" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Pilih</option>
                                <option value="lebih_tinggi">Setingkat Lebih Tinggi</option>
                                <option value="sama">Tingkat yang Sama</option>
                                <option value="lebih_rendah">Setingkat Lebih Rendah</option>
                                <option value="tidak_perlu">Tidak Perlu Pendidikan Tinggi</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: ALUMNI WIRASWASTA --}}
                <div x-show="status === 'wirausaha'" x-transition class="mb-10 p-6 bg-blue-50 rounded-2xl border-2 border-blue-200">
                    <h2 class="text-2xl font-bold pb-3 mb-6 flex items-center text-blue-800">
                        <span class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">3</span>
                        Informasi Usaha
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Dalam berapa bulan Anda menjalankan usaha? (sejak mendapat nomor ijazah)
                            </label>
                            <input type="number" 
                                   name="waktu_mulai_usaha" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Contoh: 6"
                                   min="0">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama perusahaan/usaha yang Anda kembangkan
                            </label>
                            <input type="text" 
                                   name="nama_usaha" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ old('nama_usaha', $tracerStudy->nama_usaha ?? '') }}"
                                   placeholder="Nama usaha">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Bidang usaha
                            </label>
                            <input type="text" 
                                   name="bidang_usaha" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ old('bidang_usaha', $tracerStudy->bidang_usaha ?? '') }}"
                                   placeholder="Contoh: Kuliner, Fashion, Teknologi">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Posisi/jabatan Anda saat ini
                            </label>
                            <select name="posisi_wirausaha" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Posisi</option>
                                <option value="founder">Founder</option>
                                <option value="co_founder">Co-Founder</option>
                                <option value="staff">Staff</option>
                                <option value="freelance">Freelance/Kerja Lepas</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jumlah karyawan
                            </label>
                            <input type="number" 
                                   name="jumlah_karyawan" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   value="{{ old('jumlah_karyawan', $tracerStudy->jumlah_karyawan ?? '') }}"
                                   min="0"
                                   placeholder="0">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Omzet per bulan
                            </label>
                            <input type="number" 
                                   name="omzet_usaha" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Contoh: 10000000 (tanpa titik/koma)">
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: MELANJUTKAN PENDIDIKAN --}}
                <div x-show="status === 'melanjutkan_studi'" x-transition class="mb-10 p-6 bg-purple-50 rounded-2xl border-2 border-purple-200">
                    <h2 class="text-2xl font-bold pb-3 mb-6 flex items-center text-purple-800">
                        <span class="bg-purple-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">4</span>
                        Informasi Studi Lanjut
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Sumber biaya
                            </label>
                            <select name="sumber_biaya" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Pilih Sumber Biaya</option>
                                <option value="sendiri">Biaya Sendiri</option>
                                <option value="beasiswa">Beasiswa</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Perguruan Tinggi
                            </label>
                            <input type="text" 
                                   name="nama_institusi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   value="{{ old('nama_institusi', $tracerStudy->nama_institusi ?? '') }}"
                                   placeholder="Nama perguruan tinggi">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Program Studi
                            </label>
                            <input type="text" 
                                   name="jurusan_studi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   value="{{ old('jurusan_studi', $tracerStudy->jurusan_studi ?? '') }}"
                                   placeholder="Nama program studi">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal masuk
                            </label>
                            <input type="date" 
                                   name="tanggal_masuk_studi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                {{-- SECTION 5: KOMPETENSI (f1761-f1774) --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">5</span>
                        Kompetensi
                    </h2>

                    <div class="bg-gray-50 p-6 rounded-xl mb-6">
                        <p class="text-sm text-gray-600 mb-4">
                            <strong>(A)</strong> Pada saat lulus, pada tingkat mana kompetensi di bawah ini Anda kuasai?
                        </p>
                        
                        @php
                            $kompetensi = [
                                'etika' => 'Etika',
                                'keahlian_bidang' => 'Keahlian berdasarkan bidang ilmu',
                                'bahasa_inggris' => 'Bahasa Inggris',
                                'teknologi_informasi' => 'Penggunaan Teknologi Informasi',
                                'komunikasi' => 'Komunikasi',
                                'kerja_sama_tim' => 'Kerja sama tim',
                                'pengembangan_diri' => 'Pengembangan diri'
                            ];

                            $skala = [
                                1 => 'Sangat Rendah',
                                2 => 'Rendah',
                                3 => 'Cenderung Rendah',
                                4 => 'Tinggi',
                                5 => 'Sangat Tinggi'
                            ];
                        @endphp

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kompetensi</th>
                                        @foreach($skala as $nilai => $label)
                                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500">{{ $nilai }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kompetensi as $key => $label)
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $label }}</td>
                                            @foreach($skala as $nilai => $skalatxt)
                                                <td class="px-2 py-4 text-center">
                                                    <input type="radio" 
                                                           name="kompetensi_saat_lulus[{{ $key }}]" 
                                                           value="{{ $nilai }}"
                                                           class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <p class="text-sm text-gray-600 mb-4">
                            <strong>(B)</strong> Pada saat ini, pada tingkat mana kompetensi di bawah ini diperlukan dalam pekerjaan?
                        </p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kompetensi</th>
                                        @foreach($skala as $nilai => $label)
                                            <th class="px-2 py-3 text-center text-xs font-medium text-gray-500">{{ $nilai }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kompetensi as $key => $label)
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $label }}</td>
                                            @foreach($skala as $nilai => $skalatxt)
                                                <td class="px-2 py-4 text-center">
                                                    <input type="radio" 
                                                           name="kompetensi_diperlukan[{{ $key }}]" 
                                                           value="{{ $nilai }}"
                                                           class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- SECTION 6: METODE PEMBELAJARAN (f21-f27) --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">6</span>
                        Metode Pembelajaran
                    </h2>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <p class="text-sm text-gray-600 mb-6">
                            Menurut Anda seberapa besar penekanan pada metode pembelajaran dibawah ini dilaksanakan di program studi Anda?
                        </p>

                        @php
                            $metode = [
                                'perkuliahan' => 'Perkuliahan',
                                'demonstrasi' => 'Demonstrasi',
                                'partisipasi_riset' => 'Partisipasi dalam proyek riset',
                                'magang' => 'Magang',
                                'praktikum' => 'Praktikum',
                                'kerja_lapangan' => 'Kerja Lapangan',
                                'diskusi' => 'Diskusi'
                            ];

                            $penekanan = [
                                1 => 'Sangat Besar',
                                2 => 'Besar',
                                3 => 'Cukup Besar',
                                4 => 'Kurang Besar',
                                5 => 'Tidak Sama Sekali'
                            ];
                        @endphp

                        <div class="space-y-6">
                            @foreach($metode as $key => $label)
                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $label }}</label>
                                    <div class="flex flex-wrap gap-4">
                                        @foreach($penekanan as $nilai => $labelPenekanan)
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="metode_pembelajaran[{{ $key }}]" 
                                                       value="{{ $nilai }}"
                                                       class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700">{{ $labelPenekanan }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- SECTION 7: PENCARIAN PEKERJAAN (f301-f4016) --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">7</span>
                        Pencarian Pekerjaan
                    </h2>

                    <div class="space-y-6">
                        <div class="bg-gray-50 p-6 rounded-xl">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Kapan Anda mulai mencari pekerjaan?
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="kapan_mencari_kerja" 
                                           value="sebelum_lulus"
                                           x-model="kapanCariKerja"
                                           class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-700">Sebelum lulus</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="kapan_mencari_kerja" 
                                           value="sesudah_lulus"
                                           x-model="kapanCariKerja"
                                           class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-700">Sesudah lulus</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="kapan_mencari_kerja" 
                                           value="tidak_mencari"
                                           x-model="kapanCariKerja"
                                           class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-700">Saya tidak mencari kerja</span>
                                </label>
                            </div>

                            <div x-show="kapanCariKerja === 'sebelum_lulus'" class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Perkiraan bulan sebelum lulus
                                </label>
                                <input type="number" 
                                       name="bulan_sebelum_lulus" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Contoh: 3"
                                       min="0">
                            </div>

                            <div x-show="kapanCariKerja === 'sesudah_lulus'" class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Perkiraan bulan setelah lulus
                                </label>
                                <input type="number" 
                                       name="bulan_setelah_lulus" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Contoh: 2"
                                       min="0">
                            </div>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl">
                            <label class="block text-sm font-semibold text-gray-700 mb-4">
                                Bagaimana Anda mencari pekerjaan tersebut? (Jawaban bisa lebih dari satu)
                            </label>

                            @php
                                $caraCariKerja = [
                                    'iklan_koran' => 'Melalui iklan di koran/majalah, brosur',
                                    'melamar_langsung' => 'Melamar ke perusahaan tanpa mengetahui lowongan yang ada',
                                    'bursa_kerja' => 'Pergi ke bursa/pameran kerja',
                                    'internet' => 'Mencari lewat internet/iklan online/milis',
                                    'dihubungi_perusahaan' => 'Dihubungi oleh perusahaan',
                                    'kemenakertrans' => 'Menghubungi Kemenakertrans',
                                    'agen_swasta' => 'Menghubungi agen tenaga kerja komersial/swasta',
                                    'career_center' => 'Memeroleh informasi dari pusat/kantor pengembangan karir fakultas/universitas',
                                    'kantor_kemahasiswaan' => 'Menghubungi kantor kemahasiswaan/hubungan alumni',
                                    'network' => 'Membangun jejaring (network) sejak masih kuliah',
                                    'relasi' => 'Melalui relasi (misalnya dosen, orang tua, saudara, teman, dll.)',
                                    'bisnis_sendiri' => 'Membangun bisnis sendiri',
                                    'penempatan_magang' => 'Melalui penempatan kerja atau magang',
                                    'tempat_sama' => 'Bekerja di tempat yang sama dengan tempat kerja semasa kuliah',
                                    'lainnya' => 'Lainnya'
                                ];
                            @endphp

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($caraCariKerja as $key => $label)
                                    <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                                        <input type="checkbox" 
                                               name="cara_cari_kerja[]" 
                                               value="{{ $key }}"
                                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-0.5">
                                        <span class="ml-3 text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jika lainnya, sebutkan:
                                </label>
                                <input type="text" 
                                       name="cara_cari_kerja_lainnya" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Sebutkan cara lainnya">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-6 rounded-xl">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Berapa perusahaan/instansi yang sudah Anda lamar?
                                </label>
                                <input type="number" 
                                       name="jumlah_lamaran" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="0"
                                       min="0">
                            </div>

                            <div class="bg-gray-50 p-6 rounded-xl">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Berapa yang merespons lamaran Anda?
                                </label>
                                <input type="number" 
                                       name="jumlah_respons" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="0"
                                       min="0">
                            </div>

                            <div class="bg-gray-50 p-6 rounded-xl">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Berapa yang mengundang Anda wawancara?
                                </label>
                                <input type="number" 
                                       name="jumlah_wawancara" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="0"
                                       min="0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 8: KESESUAIAN PEKERJAAN (f1601-f16014) --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">8</span>
                        Kesesuaian Pekerjaan dengan Pendidikan
                    </h2>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <p class="text-sm text-gray-600 mb-4">
                            Jika menurut Anda pekerjaan saat ini tidak sesuai dengan pendidikan, mengapa Anda mengambilnya? (Jawaban bisa lebih dari satu)
                        </p>

                        @php
                            $alasanTidakSesuai = [
                                'sudah_sesuai' => 'Pertanyaan tidak sesuai; pekerjaan saya sekarang sudah sesuai dengan pendidikan saya',
                                'belum_dapat_sesuai' => 'Saya belum mendapatkan pekerjaan yang lebih sesuai',
                                'prospek_karir' => 'Di pekerjaan ini saya memeroleh prospek karir yang baik',
                                'lebih_suka' => 'Saya lebih suka bekerja di area pekerjaan yang tidak ada hubungannya dengan pendidikan saya',
                                'dipromosikan' => 'Saya dipromosikan ke posisi yang kurang berhubungan dengan pendidikan saya dibanding posisi sebelumnya',
                                'pendapatan_lebih' => 'Saya dapat memeroleh pendapatan yang lebih tinggi di pekerjaan ini',
                                'lebih_aman' => 'Pekerjaan saya saat ini lebih aman/terjamin/secure',
                                'lebih_menarik' => 'Pekerjaan saya saat ini lebih menarik',
                                'fleksibel' => 'Pekerjaan saya saat ini lebih memungkinkan saya mengambil pekerjaan tambahan/jadwal yang fleksibel',
                                'lebih_dekat' => 'Pekerjaan saya saat ini lokasinya lebih dekat dari rumah saya',
                                'jamin_keluarga' => 'Pekerjaan saya saat ini dapat lebih menjamin kebutuhan keluarga saya',
                                'awal_karir' => 'Pada awal meniti karir ini, saya harus menerima pekerjaan yang tidak berhubungan dengan pendidikan saya',
                                'lainnya' => 'Lainnya'
                            ];
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($alasanTidakSesuai as $key => $label)
                                <label class="flex items-start p-3 border border-gray-200 rounded-lg hover:bg-white cursor-pointer">
                                    <input type="checkbox" 
                                           name="alasan_tidak_sesuai[]" 
                                           value="{{ $key }}"
                                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-0.5">
                                    <span class="ml-3 text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jika lainnya, sebutkan:
                            </label>
                            <input type="text" 
                                   name="alasan_tidak_sesuai_lainnya" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Sebutkan alasan lainnya">
                        </div>
                    </div>
                </div>

              
                {{-- SECTION 10: KONTAK TERKINI --}}
                <div class="mb-10">
                    <h2 class="text-2xl font-bold border-b-2 border-indigo-600 pb-3 mb-6 flex items-center">
                        <span class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">10</span>
                        Kontak Terkini
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Saat Ini
                            </label>
                            <input type="email" 
                                   name="email_saat_ini" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                   value="{{ old('email_saat_ini', $tracerStudy->email_saat_ini ?? '') }}"
                                   placeholder="email@example.com">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. Telepon Saat Ini
                            </label>
                            <input type="tel" 
                                   name="no_telp_saat_ini" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                   value="{{ old('no_telp_saat_ini', $tracerStudy->no_telp_saat_ini ?? '') }}"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                LinkedIn
                            </label>
                            <input type="url" 
                                   name="linkedin" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                   value="{{ old('linkedin', $tracerStudy->linkedin ?? '') }}"
                                   placeholder="https://linkedin.com/in/...">
                        </div>
                    </div>
                </div>

                
                {{-- Submit Buttons --}}
                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t-2 border-gray-200">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Kuesioner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function tracerStudyForm() {
        return {
            status: '{{ old('status_pekerjaan', $tracerStudy->status_pekerjaan ?? '') }}',
            kapanCariKerja: '{{ old('kapan_mencari_kerja', '') }}'
        }
    }
</script>
@endsection