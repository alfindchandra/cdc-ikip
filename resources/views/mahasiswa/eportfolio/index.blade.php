@extends('layouts.index')

@section('title', 'E-Portfolio Mahasiswa')
@section('home')

<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Page -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">E-Portfolio Saya</h2>
            <p class="text-sm text-gray-500 mt-1">Unggah berkas prestasi, sertifikat, dan pengalaman kerja Anda. Format yang didukung: PDF, JPG, PNG, DOC, DOCX (maks. 2 MB per berkas).</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <!-- Card Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Berkas Portfolio</h3>
            </div>

            <div class="p-6 sm:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.eportfolio.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="divide-y divide-gray-100">
                        @php
                            $items = [
                                ['field' => 'profil_file', 'path' => 'profil_path', 'label' => 'Profil Kompetensi', 'desc' => 'CV atau ringkasan kompetensi Anda'],
                                ['field' => 'pengalaman_file', 'path' => 'pengalaman_path', 'label' => 'Pengalaman Kerja', 'desc' => 'Surat keterangan kerja, magang, atau proyek'],
                                ['field' => 'prestasi_file', 'path' => 'prestasi_path', 'label' => 'Prestasi', 'desc' => 'Piagam atau bukti prestasi akademik/non-akademik'],
                                ['field' => 'sertifikat_file', 'path' => 'sertifikat_path', 'label' => 'Sertifikat', 'desc' => 'Sertifikat pelatihan, kompetensi, atau kursus'],
                            ];
                        @endphp

                        @foreach ($items as $item)
                            <div class="py-5 first:pt-0 last:pb-0 sm:flex sm:items-center sm:justify-between gap-4">
                                <div class="mb-3 sm:mb-0">
                                    <label for="{{ $item['field'] }}" class="block text-sm font-semibold text-gray-700">{{ $item['label'] }}</label>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $item['desc'] }}</p>

                                    @if($portfolio && $portfolio->{$item['path']})
                                        <a href="{{ Storage::url($portfolio->{$item['path']}) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 mt-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Lihat berkas yang sudah diunggah
                                        </a>
                                    @endif
                                </div>

                                <div class="sm:w-72">
                                    <input type="file" id="{{ $item['field'] }}" name="{{ $item['field'] }}"
                                           class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer
                                                  file:mr-3 file:py-2 file:px-3 file:rounded-l-lg file:border-0 file:text-sm file:font-medium
                                                  file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200" />
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-3">
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('profile') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition">
                            Kembali ke Profil
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection