@extends('layouts.index')

@section('title', 'Lihat E-Portfolio')
@section('home')

<section class="py-10 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">E-Portfolio {{ $mahasiswa->user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Berkas prestasi, sertifikat, dan pengalaman kerja yang diunggah mahasiswa.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Berkas Portfolio</h3>
            </div>

            <div class="p-6 sm:p-8">
                @php
                    $items = [
                        ['path' => 'profil_path', 'label' => 'Profil Kompetensi'],
                        ['path' => 'pengalaman_path', 'label' => 'Pengalaman Kerja'],
                        ['path' => 'prestasi_path', 'label' => 'Prestasi'],
                        ['path' => 'sertifikat_path', 'label' => 'Sertifikat'],
                    ];
                @endphp

                <div class="divide-y divide-gray-100">
                    @foreach ($items as $item)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $item['label'] }}</span>
                            </div>

                            @if($portfolio && $portfolio->{$item['path']})
                                <a href="{{ Storage::url($portfolio->{$item['path']}) }}" target="_blank"
                                   class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    Lihat berkas
                                </a>
                            @else
                                <span class="text-sm text-gray-400">Belum diunggah</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection