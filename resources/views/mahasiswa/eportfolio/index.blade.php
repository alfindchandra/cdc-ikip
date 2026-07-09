@extends('layouts.index')

@section('title', 'E-Portfolio Mahasiswa')
@section('home')

<section class="py-16 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="rounded-3xl bg-white shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-8 text-white">
                <h1 class="text-3xl font-black">E-Portfolio Mahasiswa</h1>
                <p class="mt-3 text-indigo-100 max-w-2xl">Unggah dokumen prestasi, sertifikat, pengalaman kerja, dan jelaskan profil kompetensi Anda secara digital agar perusahaan dapat melihat kemampuan Anda.</p>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.eportfolio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="grid gap-6 lg:grid-cols-2">
                        {{-- Profil Kompetensi --}}
                        <div class="rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Profil Kompetensi</h2>
                                <textarea name="profil_kompetensi" rows="5" class="mt-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-indigo-600" placeholder="Jelaskan keahlian, bidang kerja, dan kompetensi utama Anda">{{ old('profil_kompetensi', $portfolio->profil_kompetensi ?? '') }}</textarea>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Berkas Pendukung</label>
                                @if($portfolio && $portfolio->profil_path)
                                    <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-200 text-xs">
                                        <span class="text-emerald-600 font-medium flex items-center gap-1">✓ File sudah terunggah</span>
                                        <a href="{{ Storage::url($portfolio->profil_path) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">Lihat File</a>
                                    </div>
                                @endif
                                <input type="file" name="profil_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-[11px] text-slate-400 mt-1">Pilih file baru jika ingin mengganti file yang ada.</p>
                            </div>
                        </div>

                        {{-- Pengalaman Kerja --}}
                        <div class="rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Pengalaman Kerja</h2>
                                <textarea name="pengalaman_kerja" rows="5" class="mt-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-indigo-600" placeholder="Tuliskan pengalaman kerja, magang, atau proyek yang pernah Anda ikuti">{{ old('pengalaman_kerja', $portfolio->pengalaman_kerja ?? '') }}</textarea>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Berkas Pendukung</label>
                                @if($portfolio && $portfolio->pengalaman_path)
                                    <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-200 text-xs">
                                        <span class="text-emerald-600 font-medium flex items-center gap-1">✓ File sudah terunggah</span>
                                        <a href="{{ Storage::url($portfolio->pengalaman_path) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">Lihat File</a>
                                    </div>
                                @endif
                                <input type="file" name="pengalaman_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-[11px] text-slate-400 mt-1">Pilih file baru jika ingin mengganti file yang ada.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        {{-- Prestasi --}}
                        <div class="rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Prestasi</h2>
                                <textarea name="prestasi" rows="5" class="mt-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-indigo-600" placeholder="Cantumkan prestasi akademik, organisasi, atau kompetisi">{{ old('prestasi', $portfolio->prestasi ?? '') }}</textarea>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Berkas Pendukung</label>
                                @if($portfolio && $portfolio->prestasi_path)
                                    <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-200 text-xs">
                                        <span class="text-emerald-600 font-medium flex items-center gap-1">✓ File sudah terunggah</span>
                                        <a href="{{ Storage::url($portfolio->prestasi_path) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">Lihat File</a>
                                    </div>
                                @endif
                                <input type="file" name="prestasi_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-[11px] text-slate-400 mt-1">Pilih file baru jika ingin mengganti file yang ada.</p>
                            </div>
                        </div>

                        {{-- Sertifikat --}}
                        <div class="rounded-2xl border border-slate-200 p-5 flex flex-col justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Sertifikat</h2>
                                <textarea name="sertifikat" rows="5" class="mt-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-indigo-600" placeholder="Tuliskan sertifikat yang relevan dengan kompetensi Anda">{{ old('sertifikat', $portfolio->sertifikat ?? '') }}</textarea>
                            </div>
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Berkas Pendukung</label>
                                @if($portfolio && $portfolio->sertifikat_path)
                                    <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-200 text-xs">
                                        <span class="text-emerald-600 font-medium flex items-center gap-1">✓ File sudah terunggah</span>
                                        <a href="{{ Storage::url($portfolio->sertifikat_path) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">Lihat File</a>
                                    </div>
                                @endif
                                <input type="file" name="sertifikat_file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <p class="text-[11px] text-slate-400 mt-1">Pilih file baru jika ingin mengganti file yang ada.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">Simpan & Perbarui</button>
                        <a href="{{ route('profile') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Kembali ke Profil</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection