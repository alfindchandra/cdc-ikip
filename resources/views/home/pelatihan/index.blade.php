@extends('layouts.index')
@section('title', 'Katalog Pelatihan & Sertifikasi')
@section('home')

<section class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] flex items-center justify-center bg-slate-950 py-16 lg:py-24 overflow-hidden">
    
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/Banner.png') }}" 
             alt="Background Pelatihan" 
             class="w-full h-full object-cover object-center transform scale-100 transition-transform duration-700">
    </div>

    <div class="container mx-auto px-4 relative z-10 flex flex-col items-center justify-center text-center">
        
        <div class="w-full max-w-3xl flex flex-col items-center">
            
            <div class="inline-flex items-center gap-2 rounded-lg text-white px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider mb-6 shadow-sm">
                CDC IKIP PGRI Bojonegoro
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                Asah Potensi Diri Bersama Kelas Kompetensi Unggulan
            </h1>
            
            <p class="text-slate-300 mt-4 text-base sm:text-lg max-w-xl leading-relaxed font-medium">
                Program peningkatan keahlian kerja yang dipandu oleh instruktur berpengalaman, dirancang khusus untuk mempersiapkan karir terbaik Anda.
            </p>

            <div class="mt-8 w-full">
                <form action="{{ route('pelatihan.index') }}" method="GET" class="bg-white p-2 rounded-xl shadow-lg border border-slate-200 flex flex-col md:flex-row items-stretch gap-2">
                    
                    <div class="flex-1 flex items-center px-3 gap-3">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="Cari webinar, seminar, bimbingan karir, atau workshop..."
                            class="w-full bg-transparent border-none outline-none focus:ring-0 text-sm text-slate-800 placeholder:text-slate-400 py-3">
                    </div>
                    
                    <div class="border-t md:border-t-0 md:border-l border-slate-200 flex items-center px-3 bg-slate-50 md:bg-transparent rounded-lg md:rounded-none">
                        <i class="fas fa-filter text-slate-400 text-xs mr-2 md:hidden"></i>
                        <select name="jenis" class="w-full bg-transparent border-none text-xs font-bold text-slate-600 focus:ring-0 cursor-pointer py-2.5">
                            <option value="">Semua Kategori</option>
                            <option value="webinar" {{ request('jenis') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                            <option value="seminar" {{ request('jenis') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="bimbingan_karier" {{ request('jenis') == 'bimbingan_karier' ? 'selected' : '' }}>Bimbingan Karier</option>
                            <option value="workshop" {{ request('jenis') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-7 py-3 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2 shrink-0">
                        <span>Cari Kelas</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<section class="py-16 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4">
        
        <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4 mb-10 pb-6 border-b border-slate-200">
            <div class="flex flex-wrap items-center gap-2 p-1 bg-slate-200/60 rounded-xl w-full xl:w-auto">
                <a href="{{ route('pelatihan.index') }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ !request('jenis') ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Semua
                </a>
                <a href="{{ route('pelatihan.index', ['jenis' => 'webinar']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ request('jenis') == 'webinar' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Webinar
                </a>
                <a href="{{ route('pelatihan.index', ['jenis' => 'seminar']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ request('jenis') == 'seminar' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Seminar
                </a>
                <a href="{{ route('pelatihan.index', ['jenis' => 'bimbingan_karier']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ request('jenis') == 'bimbingan_karier' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Bimbingan Karier
                </a>
                <a href="{{ route('pelatihan.index', ['jenis' => 'workshop']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ request('jenis') == 'workshop' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Workshop
                </a>
                <a href="{{ route('pelatihan.index', ['jenis' => 'lainnya']) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all {{ request('jenis') == 'lainnya' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                   Lainnya
                </a>
            </div>

            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                Menampilkan <span class="text-slate-700 font-extrabold">{{ $pelatihans->count() }}</span> Program Aktif
            </div>
        </div>

        @if($pelatihans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($pelatihans as $kelas)
                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    
                    <div>
                        <div class="relative aspect-[16/10] bg-slate-100 overflow-hidden">
                            @if($kelas->thumbnail)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($kelas->thumbnail) }}" alt="{{ $kelas->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-50 flex items-center justify-center p-4">
                                    <i class="fas fa-graduation-cap text-indigo-300 text-4xl group-hover:rotate-6 transition-transform"></i>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                <span class="bg-white/90 backdrop-blur-md text-slate-800 font-extrabold text-[10px] uppercase tracking-wider px-2.5 py-1 rounded-md shadow-sm border border-slate-100">
                                    {{ str_replace('_', ' ', $kelas->jenis) }}
                                </span>
                            </div>

                            @if($kelas->sertifikat_template)
                                <div class="absolute bottom-3 right-3">
                                    <span class="bg-emerald-500 text-white font-bold text-[10px] tracking-wide px-2 py-0.5 rounded-md shadow-sm flex items-center gap-1">
                                        <i class="fas fa-certificate text-[9px]"></i> + Sertifikat
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-5 space-y-3">
                            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                <i class="far fa-user text-[10px]"></i>
                                <span class="line-clamp-1">Instruktur: {{ $kelas->instruktur ?? 'Tim CDC' }}</span>
                            </div>

                            <h3 class="font-extrabold text-slate-800 text-base leading-snug group-hover:text-indigo-600 transition line-clamp-2 h-12">
                                <a href="{{ route('pelatihan.show', $kelas->id) }}">
                                    {{ $kelas->judul }}
                                </a>
                            </h3>

                            <div class="grid grid-cols-2 gap-2 py-2 border-y border-slate-100 text-[11px] text-slate-500 font-semibold">
                                <div class="flex items-center gap-1">
                                    <i class="far fa-calendar-alt text-slate-400"></i>
                                    <span>{{ $kelas->tanggal_mulai->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-1 justify-end">
                                    <i class="fas fa-users text-slate-400"></i>
                                    @if($kelas->kuota)
                                        <span>{{ max(0, $kelas->kuota - $kelas->jumlah_peserta) }} Sisa Kursi</span>
                                    @else
                                        <span>Kuota Terbuka</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 pb-5 pt-2">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Investasi</div>
                                <p class="text-sm font-black text-slate-800">
                                    @if($kelas->biaya <= 0)
                                        <span class="text-emerald-600 font-extrabold">GRATIS</span>
                                    @else
                                        Rp {{ number_format($kelas->biaya, 0, ',', '.') }}
                                    @endif
                                </p>
                            </div>

                            <a href="{{ route('pelatihan.show', $kelas->id) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-900 group-hover:bg-indigo-600 text-white rounded-xl text-xs font-bold transition shadow-sm gap-1.5">
                                <span>Ikuti Kelas</span>
                                <i class="fas fa-arrow-right text-[9px] group-hover:translate-x-0.5 transition-transform"></i>
                            </a>
                        </div>
                        
                        @if($kelas->kuota > 0)
                            @php
                                $persentase = ($kelas->jumlah_peserta / $kelas->kuota) * 100;
                            @endphp
                            <div class="w-full bg-slate-100 h-1 rounded-full mt-4 overflow-hidden" title="Terisi {{ $kelas->jumlah_peserta }} dari {{ $kelas->kuota }} kuota">
                                <div class="bg-indigo-600 h-full transition-all duration-500" style="width: {{ min(100, $persentase) }}%"></div>
                            </div>
                        @else
                            <div class="w-full bg-slate-100 h-1 rounded-full mt-4 overflow-hidden">
                                <div class="bg-emerald-500 h-full w-full"></div>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm max-w-xl mx-auto p-8">
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-slate-300 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-1">Kelas Tidak Ditemukan</h3>
            <p class="text-slate-500 text-sm mb-6">Maaf, program pelatihan atau tipe kategori yang Anda pilih saat ini belum tersedia.</p>
            <a href="{{ route('pelatihan.index') }}" class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-500 transition shadow-md text-xs">
                <span>Setel Ulang Filter</span>
            </a>
        </div>
        @endif

    </div>
</section>
@endsection