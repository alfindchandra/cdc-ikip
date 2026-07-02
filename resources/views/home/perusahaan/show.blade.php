@extends('layouts.index')
@section('title', $perusahaan->nama_perusahaan)
@section('home')

<section class="py-12 bg-gradient-to-b from-blue-50 via-white to-gray-50 border-b border-gray-100">
    <div class="container mx-auto px-4">
        <div class="mb-4">
            <a href="{{ route('index.perusahaan') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar Perusahaan
            </a>
        </div>
        
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 text-center md:text-left mt-6">
            <div class="w-24 h-24 shrink-0 rounded-2xl bg-white border border-gray-200 p-3 flex items-center justify-center shadow-sm">
                @if($perusahaan->user && $perusahaan->user->avatar)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($perusahaan->user->avatar) }}" alt="{{ $perusahaan->nama_perusahaan }}" class="w-full h-full object-contain">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                @endif
            </div>
            <div class="flex-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-100 text-xs mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Mitra {{ $perusahaan->status_kerjasama ?? 'Aktif' }}
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">{{ $perusahaan->nama_perusahaan }}</h1>
                <p class="text-blue-600 font-bold text-sm mt-1">{{ $perusahaan->bidang_usaha ?? '-' }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-y-2 gap-x-4 mt-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-gray-400"></i> {{ $perusahaan->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-10 bg-gray-50/30 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider text-blue-600 border-b border-gray-100 pb-2">Kontak Utama</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-envelope text-xs"></i></span>
                            <div class="min-w-0 flex-1">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</div>
                                <p class="text-xs font-bold text-gray-700 break-all">{{ $perusahaan->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-phone text-xs"></i></span>
                            <div class="min-w-0 flex-1">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Telepon</div>
                                <p class="text-xs font-bold text-gray-700">{{ $perusahaan->no_telp ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600"><i class="fas fa-globe text-xs"></i></span>
                            <div class="min-w-0 flex-1">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Website</div>
                                @if($perusahaan->website && $perusahaan->website != '-')
                                    <a href="{{ Str::startsWith($perusahaan->website, ['http://', 'https://']) ? $perusahaan->website : 'https://' . $perusahaan->website }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline break-all">{{ $perusahaan->website }}</a>
                                @else
                                    <p class="text-xs font-bold text-gray-700">-</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-sm">
                    <h3 class="font-extrabold text-gray-800 text-sm uppercase tracking-wider text-blue-600 border-b border-gray-100 pb-2 mb-3">Tentang Perusahaan</h3>
                    <div class="text-sm text-gray-600 leading-relaxed space-y-2">
                        {!! nl2br(e($perusahaan->deskripsi)) !!}
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-2">
                    <h2 class="text-lg font-extrabold text-gray-800">
                        Lowongan Tersedia <span class="ml-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded-full">{{ $perusahaan->lowonganKerja->count() }}</span>
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Diperbarui berkala</span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($perusahaan->lowonganKerja as $lowongan)
                        <div class="p-5 rounded-2xl border border-gray-200/80 bg-white hover:border-blue-400 hover:shadow-md transition group flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1.5">
                                <h3 class="font-bold text-gray-800 text-base group-hover:text-blue-600 transition">{{ $lowongan->posisi }}</h3>
                                <div class="flex flex-wrap items-center gap-y-1 gap-x-3 text-xs text-gray-500">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded font-bold text-[11px] uppercase tracking-wider">
                                        {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                                    </span>
                                    <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt text-[10px] text-gray-400"></i> {{ $lowongan->lokasi ?? 'Remote' }}</span>
                                    <span class="flex items-center gap-1"><i class="fas fa-calendar-alt text-[10px] text-gray-400"></i> Berakhir: {{ $lowongan->tanggal_berakhir ? \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 md:pt-0">
                                <a href="{{ route('lowongan.show', $lowongan->id) }}" class="inline-flex w-full md:w-auto items-center justify-center px-7 py-2.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition shadow-sm">
                                    Lihat Detail Lowongan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 border-2 border-dashed border-gray-200 rounded-2xl bg-white p-6 shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-briefcase text-gray-300 text-xl"></i>
                            </div>
                            <h4 class="text-base font-bold text-gray-700">Belum Ada Lowongan Aktif</h4>
                            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Saat ini {{ $perusahaan->nama_perusahaan }} belum membuka atau mempublikasikan lowongan pekerjaan baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</section>
@endsection