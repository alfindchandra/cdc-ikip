@extends('layouts.index')

@section('title', 'Lihat E-Portfolio')
@section('home')

<section class="py-16 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="rounded-3xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-cyan-600 px-8 py-8 text-white">
                <h1 class="text-3xl font-black">E-Portfolio {{ $mahasiswa->user->name }}</h1>
                <p class="mt-3 text-emerald-100">Profil digital mahasiswa yang berisi dokumen prestasi, sertifikat, pengalaman kerja, dan kompetensi.</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-lg font-bold text-slate-800">Profil Kompetensi</h2>
                    <p class="mt-3 text-slate-600 whitespace-pre-line">{{ $portfolio->profil_kompetensi ?? 'Belum ada data.' }}</p>
                    @if($portfolio && $portfolio->profil_path)
                        <a href="{{ Storage::url($portfolio->profil_path) }}" target="_blank" class="mt-4 inline-flex text-sm font-semibold text-indigo-600">Lihat file profil</a>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-lg font-bold text-slate-800">Pengalaman Kerja</h2>
                    <p class="mt-3 text-slate-600 whitespace-pre-line">{{ $portfolio->pengalaman_kerja ?? 'Belum ada data.' }}</p>
                    @if($portfolio && $portfolio->pengalaman_path)
                        <a href="{{ Storage::url($portfolio->pengalaman_path) }}" target="_blank" class="mt-4 inline-flex text-sm font-semibold text-indigo-600">Lihat file pengalaman</a>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-lg font-bold text-slate-800">Prestasi</h2>
                    <p class="mt-3 text-slate-600 whitespace-pre-line">{{ $portfolio->prestasi ?? 'Belum ada data.' }}</p>
                    @if($portfolio && $portfolio->prestasi_path)
                        <a href="{{ Storage::url($portfolio->prestasi_path) }}" target="_blank" class="mt-4 inline-flex text-sm font-semibold text-indigo-600">Lihat file prestasi</a>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-lg font-bold text-slate-800">Sertifikat</h2>
                    <p class="mt-3 text-slate-600 whitespace-pre-line">{{ $portfolio->sertifikat ?? 'Belum ada data.' }}</p>
                    @if($portfolio && $portfolio->sertifikat_path)
                        <a href="{{ Storage::url($portfolio->sertifikat_path) }}" target="_blank" class="mt-4 inline-flex text-sm font-semibold text-indigo-600">Lihat file sertifikat</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
