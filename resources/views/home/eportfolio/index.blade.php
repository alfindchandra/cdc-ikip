@extends('layouts.index')

@section('title', 'E-Portfolio')
@section('home')

<section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.25),_transparent_40%)]"></div>
    <div class="container mx-auto px-4 py-20 lg:py-28 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm font-semibold backdrop-blur">
                <i class="fas fa-folder-open"></i>
                CDC IKIP PGRI Bojonegoro
            </span>
            <h1 class="mt-6 text-4xl sm:text-5xl font-black tracking-tight">E-Portfolio</h1>
            <p class="mt-5 text-lg sm:text-xl text-slate-200 leading-relaxed">
                E-portfolio dioperasionalkan sebagai fitur dalam sistem yang memungkinkan mahasiswa dan alumni menyimpan serta menampilkan dokumen prestasi, sertifikat, pengalaman kerja, dan profil kompetensi secara digital.
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] items-start">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-800">Tujuan operasional</h2>
                <p class="mt-4 text-slate-600 leading-relaxed">
                    E-portfolio menjadi wadah digital yang memudahkan mahasiswa dan alumni mempresentasikan rekam jejak akademik maupun non-akademik secara terstruktur. Fitur ini membantu pihak institusi, mitra industri, dan calon pemberi kerja melihat kompetensi secara lebih cepat dan akurat.
                </p>
                <div class="mt-6 space-y-3">
                    <div class="flex items-start gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
                        <div class="mt-1 flex h-9 w-9 items-center justify-center rounded-full bg-emerald-600 text-white">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Kemampuan unggah dokumen</h3>
                            <p class="text-sm text-slate-600">Pengguna dapat mengunggah dokumen prestasi, sertifikat, surat keterangan, dan bukti pengalaman kerja dengan mudah.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                        <div class="mt-1 flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Kelengkapan konten</h3>
                            <p class="text-sm text-slate-600">Setiap e-portfolio dapat memuat profil kompetensi, pencapaian, pengalaman kerja, serta dokumen pendukung yang relevan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-2xl border border-violet-100 bg-violet-50 p-4">
                        <div class="mt-1 flex h-9 w-9 items-center justify-center rounded-full bg-violet-600 text-white">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Keterpakaian dalam rekrutmen</h3>
                            <p class="text-sm text-slate-600">E-portfolio menjadi referensi penting dalam proses rekrutmen dan penilaian calon pekerja yang lebih objektif.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-xl font-bold text-slate-800">Indikator operasional</h2>
                <ul class="mt-6 space-y-4 text-slate-600">
                    <li class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                        <span>Pengguna mampu mengunggah dokumen secara lengkap dan terstruktur.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                        <span>Konten e-portfolio mencerminkan profil kompetensi, prestasi, dan pengalaman kerja.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-indigo-600"></span>
                        <span>E-portfolio dapat digunakan sebagai alat pendukung dalam proses rekrutmen dan penilaian industri.</span>
                    </li>
                </ul>

                <div class="mt-8 rounded-2xl bg-slate-900 p-6 text-white">
                    <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Manfaat utama</p>
                    <p class="mt-3 text-lg font-semibold">Memperkuat daya saing mahasiswa dan alumni melalui penyajian profil digital yang profesional.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
