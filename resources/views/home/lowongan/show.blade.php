@extends('layouts.index')
@section('title', $lowongan->posisi)
@section('home')

<section class="pt-22 text-white relative overflow-hidden">
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-5xl mx-auto">
            

            <h1 class="text-3xl md:text-5xl font-extrabold mb-6 tracking-tight leading-tight text-slate-900">
                {{ $lowongan->posisi }}
            </h1>

            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if($lowongan->perusahaan->user)
                <span class="px-3 py-1 bg-purple-500/10 text-slate-800 border border-purple-500/20 rounded-full text-xs font-semibold tracking-wide uppercase">
                    {{ $lowongan->perusahaan->user->name ?? 'Perusahaan Mitra' }}
                </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-900">
                @if($lowongan->perusahaan)
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-lg border border-slate-700/50">
                    <i class="fas fa-building text-purple-400"></i>
                    <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}</span>
                </div>
                @endif
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt text-rose-400"></i>
                    <span>{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-alt text-emerald-400"></i>
                    <span>Dibuat {{ $lowongan->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-slate-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl border border-slate-100 p-6 md:p-8 shadow-sm transition-all hover:shadow-md">
                    <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center border-b border-slate-100 pb-4">
                        <i class="fas fa-briefcase text-blue-600 mr-3"></i>Informasi Pekerjaan
                    </h2>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                            <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-1">Kisaran Gaji</p>
                            <p class="text-lg font-extrabold text-slate-700">
                                @if($lowongan->gaji_min && $lowongan->gaji_max)
                                    Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                @elseif($lowongan->gaji_max)
                                    Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                @else
                                    Negosiasi
                                @endif
                            </p>
                        </div>

                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                            <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-1">Lokasi Kerja</p>
                            <p class="text-base font-bold text-slate-800">{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</p>
                        </div>

                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                            <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-1">Batas Lamaran</p>
                            <p class="text-base font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        @if($lowongan->jumlah_posisi)
                        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                            <p class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-1">Jumlah Kebutuhan</p>
                            <p class="text-base font-bold text-slate-800">{{ $lowongan->jumlah_posisi }} Orang</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 md:p-8 shadow-sm space-y-8">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3">
                            <i class="fas fa-file-alt text-purple-600 mr-3"></i>Deskripsi Pekerjaan
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm">
                            {!! nl2br(e($lowongan->deskripsi)) !!}
                        </div>
                    </div>

                    @if($lowongan->persyaratan)
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3">
                            <i class="fas fa-clipboard-list text-purple-600 mr-3"></i>Persyaratan Wajib
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm">
                            {!! nl2br(e($lowongan->persyaratan)) !!}
                        </div>
                    </div>
                    @endif

                    @if($lowongan->kualifikasi)
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center border-b border-slate-100 pb-3">
                            <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>Kualifikasi Pendidikan & Skill
                        </h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm">
                            {!! nl2br(e($lowongan->kualifikasi)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                @if($lowongan->perusahaan)
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm sticky top-24 transition-all hover:shadow-md">
                    <div class="mb-5 text-center relative">
                        @if($lowongan->perusahaan->user && $lowongan->perusahaan->user->avatar)
                            <div class="w-24 h-24 mx-auto rounded-2xl overflow-hidden border-2 border-purple-100 p-1 bg-white shadow-sm">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($lowongan->perusahaan->user->avatar) }}" 
                                     alt="{{ $lowongan->perusahaan->user->name ?? 'Perusahaan Mitra' }}" 
                                     class="w-full h-full object-cover rounded-xl">
                            </div>
                        @else
                            <div class="w-24 h-24 mx-auto rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shadow-inner">
                                <i class="fas fa-building text-3xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="text-center mb-5">
                        <h3 class="text-xl font-bold text-slate-900 mb-2 px-1">
                            {{ $lowongan->perusahaan->user->name ?? 'Perusahaan Mitra' }}
                        </h3>
                        @if($lowongan->perusahaan->deskripsi)
                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed px-2">
                            {{ $lowongan->perusahaan->deskripsi }}
                        </p>
                        @endif
                    </div>

                    <div class="space-y-3.5 py-4 border-t border-b border-slate-100 my-5 text-sm">
                        @if($lowongan->perusahaan->alamat)
                        <div class="flex items-start text-slate-600 gap-3">
                            <i class="fas fa-map-marker-alt text-slate-400 mt-0.5 flex-shrink-0 w-4 text-center"></i>
                            <span class="text-xs leading-normal">{{ $lowongan->perusahaan->alamat }}</span>
                        </div>
                        @endif

                        @if($lowongan->perusahaan->no_telepon)
                        <div class="flex items-center text-slate-600 gap-3">
                            <i class="fas fa-phone text-slate-400 flex-shrink-0 w-4 text-center"></i>
                            <a href="tel:{{ $lowongan->perusahaan->no_telepon }}" class="text-xs hover:text-blue-600 font-medium transition-colors">
                                {{ $lowongan->perusahaan->no_telepon }}
                            </a>
                        </div>
                        @endif

                        @if($lowongan->perusahaan->email)
                        <div class="flex items-center text-slate-600 gap-3">
                            <i class="fas fa-envelope text-slate-400 flex-shrink-0 w-4 text-center"></i>
                            <a href="mailto:{{ $lowongan->perusahaan->email }}" class="text-xs hover:text-blue-600 font-medium transition-colors truncate">
                                {{ $lowongan->perusahaan->email }}
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-2.5">
                        @if(!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'perusahaan'))
                        <a href="{{ route('mahasiswa.lowongan.apply', $lowongan->id) }}" 
                           class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl text-sm font-bold shadow-sm hover:shadow shadow-blue-200 transition-all active:scale-[0.98]">
                            <i class="fas fa-paper-plane mr-2"></i>Lamar Sekarang
                        </a>
                        @endif


                        <a href="{{ route('index.lowongan') }}" 
                           class="flex items-center justify-center w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl text-sm font-bold transition-all active:scale-[0.98]">
                            <i class="fas fa-arrow-left mr-2 text-xs"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Styling interaksi teks hasil nl2br di dalam class .prose */
    .prose p {
        margin-bottom: 0.75rem;
    }
    .prose p:last-child {
        margin-bottom: 0;
    }
    .prose strong {
        font-weight: 700;
        color: #0f172a;
    }
</style>
@endpush