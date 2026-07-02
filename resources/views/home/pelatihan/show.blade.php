@extends('layouts.index')
@section('title', $kelas->judul)
@section('home')

<section class="py-12 bg-slate-50 min-h-screen">
    <div class="container mx-auto px-4 max-w-6xl">
        
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('pelatihan.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-indigo-600 transition-all bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100">
                <i class="fas fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Katalog</span>
            </a>
            <span class="bg-indigo-50 text-indigo-700 font-extrabold text-[10px] uppercase tracking-wider px-3 py-1.5 rounded-lg border border-indigo-100">
                {{ str_replace('_', ' ', $kelas->jenis) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="relative aspect-[16/9] bg-slate-100 w-full">
                        @if($kelas->thumbnail)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($kelas->thumbnail) }}" alt="{{ $kelas->judul }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-50 flex items-center justify-center p-6">
                                <i class="fas fa-graduation-cap text-indigo-300 text-7xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6 sm:p-8 space-y-4">
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight leading-tight">
                            {{ $kelas->judul }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-slate-500 pt-2 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <i class="far fa-user text-[11px]"></i>
                                </div>
                                <span>Instruktur: <span class="text-slate-700">{{ $kelas->instruktur ?? 'Tim CDC' }}</span></span>
                            </div>
                            @if($kelas->sertifikat_template)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                    <i class="fas fa-certificate text-[11px]"></i>
                                </div>
                                <span class="text-emerald-600">E-Sertifikat Tersedia</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-4">
                    <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-align-left text-indigo-500 text-sm"></i>
                        Deskripsi Kelas
                    </h2>
                    <div class="text-slate-600 text-sm leading-relaxed space-y-3 whitespace-pre-line">
                        {{-- Menggunakan nl2br jika deskripsi berupa text biasa, atau {!! $kelas->deskripsi !!} jika menggunakan rich text editor --}}
                        {!! nl2br(e($kelas->deskripsi ?? 'Belum ada deskripsi lengkap untuk program ini.')) !!}
                    </div>
                </div>

            </div>

            <div class="space-y-6 lg:sticky lg:top-6">
                
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl p-6 space-y-6">
                    
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Investasi</div>
                        <p class="text-3xl font-black text-slate-800">
                            @if($kelas->biaya <= 0)
                                <span class="text-emerald-600">GRATIS</span>
                            @else
                                <span class="text-indigo-600">Rp {{ number_format($kelas->biaya, 0, ',', '.') }}</span>
                            @endif
                        </p>
                    </div>

                    <hr class="border-slate-100">

                    <div class="space-y-4">
                        <div class="flex items-start gap-3 text-xs font-bold">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="far fa-calendar-alt text-sm"></i>
                            </div>
                            <div>
                                <div class="text-slate-400 font-medium">Tanggal Pelaksanaan</div>
                                <div class="text-slate-700 mt-0.5">{{ $kelas->tanggal_mulai->translatedFormat('d F Y') }}</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 text-xs font-bold">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                                <i class="fas fa-users text-sm"></i>
                            </div>
                            <div class="w-full">
                                <div class="text-slate-400 font-medium">Ketersediaan Kuota</div>
                                <div class="text-slate-700 mt-0.5 flex items-center justify-between">
                                    @if($kelas->kuota)
                                        <span>{{ max(0, $kelas->kuota - $kelas->jumlah_peserta) }} / {{ $kelas->kuota }} Kursi Tersisa</span>
                                    @else
                                        <span>Kuota Terbuka</span>
                                    @endif
                                </div>
                                
                                @if($kelas->kuota > 0)
                                    @php $persentase = ($kelas->jumlah_peserta / $kelas->kuota) * 100; @endphp
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-indigo-600 h-full transition-all duration-500" style="width: {{ min(100, $persentase) }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        @if($kelas->kuota && ($kelas->jumlah_peserta >= $kelas->kuota))
                            <button disabled class="w-full bg-slate-200 text-slate-400 cursor-not-allowed py-3.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                                <i class="fas fa-ban"></i>
                                <span>Kuota Sudah Penuh</span>
                            </button>
                        @else
                        <form action="{{ route('mahasiswa.pelatihan.daftar', $kelas->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-xl text-sm font-bold transition shadow-md shadow-indigo-200 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Daftar Sekarang
                            </button>
                        </form>
                            
                        @endif
                    </div>

                </div>

                <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 text-slate-800/20 text-8xl font-black select-none">
                        CDC
                    </div>
                    <div class="relative z-10 space-y-2">
                        <div class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">Butuh Bantuan?</div>
                        <h4 class="text-sm font-bold">Hubungi CDC IKIP PGRI Bojonegoro</h4>
                        <p class="text-slate-400 text-xs leading-relaxed">Jika mengalami kendala pendaftaran pendaftaran kelas, hubungi helpdesk kami.</p>
                        <div class="pt-2">
                            <a href="https://wa.me/6285162671337" target="_blank" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg text-[11px] font-bold transition">
                                <i class="fab fa-whatsapp text-emerald-400"></i> Hubungi WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        @if(isset($kelasLainnya) && $kelasLainnya->count() > 0)
            <div class="mt-16 pt-8 border-t border-slate-200">
                <h3 class="text-xl font-black text-slate-800 mb-6">Kelas Sejenis Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($kelasLainnya as $item)
                        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ str_replace('_', ' ', $item->jenis) }}
                                </span>
                                <h4 class="font-bold text-slate-800 text-sm mt-2 line-clamp-2 min-h-[40px]">
                                    <a href="{{ route('pelatihan.show', $item->id) }}" class="hover:text-indigo-600">
                                        {{ $item->judul }}
                                    </a>
                                </h4>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-50 text-xs font-bold">
                                <span class="text-slate-700">
                                    @if($item->biaya <= 0) GRATIS @else Rp {{ number_format($item->biaya, 0, ',', '.') }} @endif
                                </span>
                                <a href="{{ route('pelatihan.show', $item->id) }}" class="text-indigo-600 hover:underline text-[11px]">Detail <i class="fas fa-chevron-right text-[9px]"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

@endsection