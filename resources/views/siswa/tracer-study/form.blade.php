@extends('layouts.app')

{{-- Pastikan file layouts/app.blade.php Anda sudah mengimpor Tailwind CSS dan Alpine.js --}}

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        {{-- Card Header (Tailwind Equivalent) --}}
        <div class="bg-indigo-600 p-6 text-white rounded-t-lg">
            <h1 class="text-2xl font-bold flex items-center mb-1">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.47 9.246 5 7.5 5S4.168 5.47 3 6.253v13C4.168 18.47 5.754 18 7.5 18s3.332.47 4.5 1.247m0-13C13.168 5.47 14.754 5 16.5 5s3.332.47 4.5 1.253v13C19.832 18.47 18.246 18 16.5 18s-3.332.47-4.5 1.247"></path>
                </svg>
                Tracer Study Alumni
            </h1>
            <p class="text-sm opacity-90">Bantu kami melacak perkembangan karir Anda setelah lulus</p>
        </div>

        {{-- Card Body --}}
        <div class="p-6" x-data="{ statusPekerjaan: '{{ old('status_pekerjaan', $tracerStudy->status_pekerjaan ?? '') }}' }">
            {{-- Success Alert --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert" x-data="{ open: true }" x-show="open" x-init="setTimeout(() => open = false, 5000)">
                    <p class="font-bold">Sukses!</p>
                    <p class="flex justify-between items-center">{{ session('success') }} 
                        <button @click="open = false" class="text-green-700 hover:text-green-900 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </p>
                </div>
            @endif

            {{-- Existing Data Alert --}}
            @if(isset($tracerStudy) && $tracerStudy)
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data Terisi
                    </p>
                    <p class="mt-1 text-sm">Anda sudah mengisi tracer study pada **{{ $tracerStudy->tanggal_isi->format('d F Y') }}**. Anda dapat memperbarui data di bawah ini.</p>
                </div>
            @endif

            <form action="{{ route('siswa.tracer-study.store') }}" method="POST">
                @csrf

                {{-- Status Saat Ini --}}
                <div class="mb-8">
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Status Saat Ini</h2>
                    
                    <div class="mb-4">
                        <label for="status_pekerjaan" class="block text-sm font-medium text-gray-700 required">Status Pekerjaan</label>
                        <select name="status_pekerjaan" id="status_pekerjaan" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 @error('status_pekerjaan') border-red-500 @enderror" 
                                required x-model="statusPekerjaan">
                            <option value="">-- Pilih Status --</option>
                            <option value="bekerja">Bekerja</option>
                            <option value="wirausaha">Wirausaha</option>
                            <option value="melanjutkan_studi">Melanjutkan Studi</option>
                            <option value="belum_bekerja">Belum Bekerja</option>
                        </select>
                        @error('status_pekerjaan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Section Bekerja (Controlled by Alpine.js) --}}
                <div x-show="statusPekerjaan === 'bekerja'" class="mb-8 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300 ease-in-out" x-cloak>
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Informasi Pekerjaan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                            <input type="text" name="nama_perusahaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('nama_perusahaan', $tracerStudy->nama_perusahaan ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Posisi/Jabatan</label>
                            <input type="text" name="posisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('posisi', $tracerStudy->posisi ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Bidang Pekerjaan</label>
                            <input type="text" name="bidang_pekerjaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('bidang_pekerjaan', $tracerStudy->bidang_pekerjaan ?? '') }}" placeholder="Contoh: IT, Marketing, Finance">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Jenis Perusahaan</label>
                            <select name="jenis_perusahaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">-- Pilih --</option>
                                <option value="pemerintah" {{ old('jenis_perusahaan', $tracerStudy->jenis_perusahaan ?? '') == 'pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                                <option value="swasta" {{ old('jenis_perusahaan', $tracerStudy->jenis_perusahaan ?? '') == 'swasta' ? 'selected' : '' }}>Swasta</option>
                                <option value="bumn" {{ old('jenis_perusahaan', $tracerStudy->jenis_perusahaan ?? '') == 'bumn' ? 'selected' : '' }}>BUMN</option>
                                <option value="startup" {{ old('jenis_perusahaan', $tracerStudy->jenis_perusahaan ?? '') == 'startup' ? 'selected' : '' }}>Startup</option>
                                <option value="lainnya" {{ old('jenis_perusahaan', $tracerStudy->jenis_perusahaan ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Alamat Perusahaan</label>
                        <textarea name="alamat_perusahaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" rows="2">{{ old('alamat_perusahaan', $tracerStudy->alamat_perusahaan ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Penghasilan per Bulan</label>
                            <select name="penghasilan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">-- Pilih Range --</option>
                                <option value="< 3 juta" {{ old('penghasilan', $tracerStudy->penghasilan ?? '') == '< 3 juta' ? 'selected' : '' }}>< Rp 3.000.000</option>
                                <option value="3-5 juta" {{ old('penghasilan', $tracerStudy->penghasilan ?? '') == '3-5 juta' ? 'selected' : '' }}>Rp 3.000.000 - Rp 5.000.000</option>
                                <option value="5-7 juta" {{ old('penghasilan', $tracerStudy->penghasilan ?? '') == '5-7 juta' ? 'selected' : '' }}>Rp 5.000.000 - Rp 7.000.000</option>
                                <option value="7-10 juta" {{ old('penghasilan', $tracerStudy->penghasilan ?? '') == '7-10 juta' ? 'selected' : '' }}>Rp 7.000.000 - Rp 10.000.000</option>
                                <option value="> 10 juta" {{ old('penghasilan', $tracerStudy->penghasilan ?? '') == '> 10 juta' ? 'selected' : '' }}>> Rp 10.000.000</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Relevansi dengan Jurusan</label>
                            <select name="relevansi_pekerjaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">-- Pilih --</option>
                                <option value="sangat_relevan" {{ old('relevansi_pekerjaan', $tracerStudy->relevansi_pekerjaan ?? '') == 'sangat_relevan' ? 'selected' : '' }}>Sangat Relevan</option>
                                <option value="relevan" {{ old('relevansi_pekerjaan', $tracerStudy->relevansi_pekerjaan ?? '') == 'relevan' ? 'selected' : '' }}>Relevan</option>
                                <option value="cukup_relevan" {{ old('relevansi_pekerjaan', $tracerStudy->relevansi_pekerjaan ?? '') == 'cukup_relevan' ? 'selected' : '' }}>Cukup Relevan</option>
                                <option value="tidak_relevan" {{ old('relevansi_pekerjaan', $tracerStudy->relevansi_pekerjaan ?? '') == 'tidak_relevan' ? 'selected' : '' }}>Tidak Relevan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Cara Mendapat Pekerjaan</label>
                            <input type="text" name="cara_mendapat_pekerjaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('cara_mendapat_pekerjaan', $tracerStudy->cara_mendapat_pekerjaan ?? '') }}" placeholder="Contoh: Job Fair, Referensi, Portal Lowongan">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Waktu Tunggu Kerja (Bulan)</label>
                            <input type="number" name="waktu_tunggu_kerja" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('waktu_tunggu_kerja', $tracerStudy->waktu_tunggu_kerja ?? '') }}" min="0" placeholder="Berapa bulan setelah lulus?">
                        </div>
                    </div>
                </div>

                {{-- Section Wirausaha (Controlled by Alpine.js) --}}
                <div x-show="statusPekerjaan === 'wirausaha'" class="mb-8 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300 ease-in-out" x-cloak>
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Informasi Usaha</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Usaha</label>
                            <input type="text" name="nama_usaha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('nama_usaha', $tracerStudy->nama_usaha ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('bidang_usaha', $tracerStudy->bidang_usaha ?? '') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Jumlah Karyawan</label>
                            <input type="number" name="jumlah_karyawan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('jumlah_karyawan', $tracerStudy->jumlah_karyawan ?? '') }}" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Omzet per Bulan</label>
                            <select name="omzet_usaha" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">-- Pilih Range --</option>
                                <option value="< 10 juta" {{ old('omzet_usaha', $tracerStudy->omzet_usaha ?? '') == '< 10 juta' ? 'selected' : '' }}>< Rp 10.000.000</option>
                                <option value="10-50 juta" {{ old('omzet_usaha', $tracerStudy->omzet_usaha ?? '') == '10-50 juta' ? 'selected' : '' }}>Rp 10 juta - Rp 50 juta</option>
                                <option value="50-100 juta" {{ old('omzet_usaha', $tracerStudy->omzet_usaha ?? '') == '50-100 juta' ? 'selected' : '' }}>Rp 50 juta - Rp 100 juta</option>
                                <option value="> 100 juta" {{ old('omzet_usaha', $tracerStudy->omzet_usaha ?? '') == '> 100 juta' ? 'selected' : '' }}>> Rp 100 juta</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section Melanjutkan Studi (Controlled by Alpine.js) --}}
                <div x-show="statusPekerjaan === 'melanjutkan_studi'" class="mb-8 p-4 border border-gray-200 rounded-lg bg-gray-50 transition-all duration-300 ease-in-out" x-cloak>
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Informasi Studi Lanjutan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Institusi</label>
                            <input type="text" name="nama_institusi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('nama_institusi', $tracerStudy->nama_institusi ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Jenjang Studi</label>
                            <select name="jenjang_studi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">-- Pilih --</option>
                                <option value="d3" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 'd3' ? 'selected' : '' }}>D3</option>
                                <option value="s1" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 's1' ? 'selected' : '' }}>S1</option>
                                <option value="s2" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 's2' ? 'selected' : '' }}>S2</option>
                                <option value="s3" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 's3' ? 'selected' : '' }}>S3</option>
                                <option value="kursus" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 'kursus' ? 'selected' : '' }}>Kursus/Sertifikasi</option>
                                <option value="pelatihan" {{ old('jenjang_studi', $tracerStudy->jenjang_studi ?? '') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Jurusan/Program Studi</label>
                            <input type="text" name="jurusan_studi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('jurusan_studi', $tracerStudy->jurusan_studi ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Sumber Biaya</label>
                            <input type="text" name="sumber_biaya" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('sumber_biaya', $tracerStudy->sumber_biaya ?? '') }}" placeholder="Contoh: Beasiswa, Pribadi">
                        </div>
                    </div>
                </div>

                {{-- Kepuasan & Feedback --}}
                <div class="mb-8">
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Kepuasan & Saran</h2>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kepuasan terhadap Pendidikan (1-5)</label>
                        <div class="flex flex-wrap gap-4">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="flex items-center">
                                    {{-- Radio buttons are intentionally not given p-2 --}}
                                    <input class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" type="radio" name="kepuasan_pendidikan" id="kepuasan{{ $i }}" value="{{ $i }}" {{ old('kepuasan_pendidikan', $tracerStudy->kepuasan_pendidikan ?? '') == $i ? 'checked' : '' }}>
                                    <label class="ml-2 block text-sm text-gray-700" for="kepuasan{{ $i }}">
                                        {{ $i }} @if($i == 1) (Buruk) @elseif($i == 5) (Sangat Baik) @endif
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Saran untuk Kurikulum</label>
                        <textarea name="saran_kurikulum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" rows="3">{{ old('saran_kurikulum', $tracerStudy->saran_kurikulum ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Saran untuk Fasilitas</label>
                        <textarea name="saran_fasilitas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" rows="3">{{ old('saran_fasilitas', $tracerStudy->saran_fasilitas ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Saran Umum</label>
                        <textarea name="saran_umum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" rows="3">{{ old('saran_umum', $tracerStudy->saran_umum ?? '') }}</textarea>
                    </div>
                </div>

                {{-- Kontak Terkini --}}
                <div class="mb-8">
                    <h2 class="text-xl font-semibold border-b pb-2 mb-4 text-gray-700">Kontak Terkini</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Email Saat Ini</label>
                            <input type="email" name="email_saat_ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('email_saat_ini', $tracerStudy->email_saat_ini ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">No. Telepon Saat Ini</label>
                            <input type="text" name="no_telp_saat_ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('no_telp_saat_ini', $tracerStudy->no_telp_saat_ini ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">LinkedIn</label>
                            <input type="url" name="linkedin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" value="{{ old('linkedin', $tracerStudy->linkedin ?? '') }}" placeholder="https://linkedin.com/in/...">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection