@extends('layouts.index')
@section('title', 'Mitra Perusahaan')
@section('home')
<section class="py-16 bg-gradient-to-b from-blue-50 via-white to-gray-50/50 border-b border-gray-100">
    <div class="container mx-auto px-4 text-center">
        <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-700 px-4 py-1.5 text-xs font-semibold border border-blue-200/50 mb-4">
            <i class="fas fa-building text-blue-500"></i> CDC IKIP PGRI Bojonegoro
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-800 tracking-tight">Eksplorasi Mitra Perusahaan</h1>
        <div class="mx-auto mt-3 h-[6px] w-24 rounded-full bg-blue-600"></div>
        <p class="text-gray-500 mt-3 text-base max-w-md mx-auto">Kenali lebih dekat berbagai mitra perusahaan tepercaya dan lowongan aktif yang tersedia.</p>
    </div>
</section>
 
<section class="py-8 bg-gray-50/50 border-b border-gray-200/60">
    <div class="container mx-auto px-4">
        <form action="{{ route('index.perusahaan') }}" method="GET" class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-search mr-1.5 text-blue-500"></i>Kata Kunci
                    </label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Nama perusahaan atau deskripsi..."
                        class="w-full border border-gray-200 bg-white rounded-xl py-2.5 px-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                </div>

                <div class="flex flex-row gap-3 min-w-full lg:min-w-0">
                    @if(request()->has('keyword'))
                    <a href="{{ route('index.perusahaan') }}"
                        class="flex-1 lg:flex-none px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-50 transition flex items-center justify-center gap-2">
                        <i class="fas fa-redo text-xs"></i> Reset
                    </a>
                    @endif
                    <button type="submit"
                        class="flex-1 lg:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 hover:shadow-md transition-all">
                        <i class="fas fa-search"></i> CARI
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="py-10 bg-gray-50/30 min-h-screen" x-data="{ selectedId: null, perusahaans: [] }" x-init="
    perusahaans = [
        @foreach($perusahaanTerbaru as $index => $perusahaan)
        {
            id: {{ $perusahaan->id }},
            nama: '{{ addslashes($perusahaan->nama_perusahaan) }}',
            logo: '{{ ($perusahaan->user && $perusahaan->user->avatar) ? \Illuminate\Support\Facades\Storage::url($perusahaan->user->avatar) : '' }}',
            bidang: '{{ addslashes($perusahaan->bidang_usaha ?? '-') }}',
            alamat: '{{ addslashes($perusahaan->alamat ?? '-') }}',
            telepon: '{{ $perusahaan->no_telp ?? '-' }}',
            email: '{{ $perusahaan->email ?? '-' }}',
            website: '{{ $perusahaan->website ?? '-' }}',
            status_kerjasama: '{{ $perusahaan->status_kerjasama ?? 'Aktif' }}',
            deskripsi: `{!! nl2br(e($perusahaan->deskripsi)) !!}`,
            lowongans: [
                @foreach($perusahaan->lowonganKerja as $lowongan)
                {
                    id: {{ $lowongan->id }},
                    posisi: '{{ addslashes($lowongan->posisi) }}',
                    tipe: '{{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}',
                    lokasi: '{{ $lowongan->lokasi ?? 'Remote' }}',
                    detail_url: '{{ route('lowongan.show', $lowongan->id) }}'
                },
                @endforeach
            ]
        },
        @endforeach
    ];
    if(perusahaans.length > 0) { selectedId = perusahaans[0].id; }
">
    <div class="container mx-auto px-4">
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-sm font-bold border border-blue-100">
                Total {{ $perusahaanTerbaru->count() }} Perusahaan
            </span>
            <span class="text-sm text-gray-500">Menampilkan daftar mitra aktif terbaru</span>
        </div>

        @if($perusahaanTerbaru->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <div class="lg:col-span-2 space-y-3 lg:max-h-[calc(100vh-14rem)] lg:overflow-y-auto pr-1 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                <template x-for="item in perusahaans" :key="item.id">
                    <div>
                        <button type="button" @click="selectedId = item.id"
                            class="w-full text-left rounded-2xl border p-4 transition shadow-sm hover:shadow-md focus:outline-none"
                            :class="selectedId === item.id ? 'border-blue-600 ring-1 ring-blue-600/30 bg-blue-50/20' : 'border-gray-200 bg-white hover:border-blue-400/60'">
                            
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 shrink-0 rounded-xl bg-slate-50 border border-slate-100 p-1.5 flex items-center justify-center overflow-hidden">
                                    <template x-if="item.logo">
                                        <img :src="item.logo" :alt="item.nama" class="w-full h-full object-contain">
                                    </template>
                                    <template x-if="!item.logo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                                    </template>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-slate-800 leading-snug line-clamp-2" x-text="item.nama"></h3>
                                    <p class="text-xs font-semibold text-blue-600 mt-0.5 line-clamp-1" x-text="item.bidang"></p>
                                    
                                    <div class="mt-2 flex items-start gap-1.5 text-xs text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5 text-slate-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        <span class="line-clamp-1 leading-snug" x-text="item.alamat"></span>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between gap-2 text-[11px]">
                                        <span class="text-gray-400 font-medium" x-text="item.lowongans.length + ' Lowongan'"></span>
                                    </div>
                                </div>
                            </div>
                        </button>

                        <div class="block lg:hidden mt-2 border border-gray-200 bg-white rounded-2xl p-4 space-y-4 shadow-inner" x-show="selectedId === item.id" x-collapse>
                            <div class="bg-gray-50 p-3 rounded-xl space-y-1.5 text-xs">
                                <div><span class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">Email:</span> <span class="text-gray-700 font-medium" x-text="item.email"></span></div>
                                <div><span class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">Telepon:</span> <span class="text-gray-700 font-medium" x-text="item.telepon"></span></div>
                                <div><span class="font-bold text-gray-400 uppercase tracking-wider text-[10px]">Website:</span> <span class="text-blue-600 font-medium" x-text="item.website"></span></div>
                            </div>
                            <div class="text-sm text-gray-600" x-html="item.deskripsi"></div>
                            
                            <div class="pt-4 border-t border-gray-100">
                                <a :href="'/perusahaan/' + item.id" class="flex items-center justify-center w-full px-4 py-2.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 font-bold rounded-xl text-xs transition gap-2">
                                    <span>Lihat Profil & Lowongan Lengkap</span> <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="hidden lg:block lg:col-span-3">
                <div class="sticky top-24 max-h-[calc(100vh-14rem)] overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-sm [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                    <template x-for="item in perusahaans" :key="item.id">
                        <div x-show="selectedId === item.id" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="relative px-6 py-6 border-b border-gray-100 bg-gradient-to-b from-blue-50/20 to-white overflow-hidden">
                                <div class="flex items-start justify-between gap-4">
                                    
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        <div class="w-16 h-16 shrink-0 rounded-xl bg-white border border-gray-200 p-2 flex items-center justify-center shadow-sm">
                                            <template x-if="item.logo">
                                                <img :src="item.logo" :alt="item.nama" class="w-full h-full object-contain">
                                            </template>
                                            <template x-if="!item.logo">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h2 class="text-lg font-extrabold text-gray-800 leading-snug" x-text="item.nama"></h2>
                                            <p class="text-sm font-bold text-blue-600 mt-1" x-text="item.bidang"></p>
                                            
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                <span class="inline-flex items-center rounded-full bg-white border border-gray-200 text-gray-600 px-2.5 py-0.5 text-[11px] font-semibold shadow-sm" x-text="'Status: ' + item.status_kerjasama"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="shrink-0">
                                        <a :href="'/perusahaan/' + item.id" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm whitespace-nowrap">
                                            <span>Kunjungi Profil</span>
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    </div>

                                </div>

                                <div class="mt-5 grid grid-cols-3 gap-3">
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><i class="fas fa-envelope text-xs"></i></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</div>
                                        <p class="mt-0.5 text-xs font-bold text-gray-700 line-clamp-1" x-text="item.email"></p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><i class="fas fa-phone text-xs"></i></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Telepon</div>
                                        <p class="mt-0.5 text-xs font-bold text-gray-700 line-clamp-1" x-text="item.telepon"></p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><i class="fas fa-globe text-xs"></i></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Website</div>
                                        <p class="mt-0.5 text-xs font-bold text-blue-600 line-clamp-1" x-text="item.website"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6 text-sm text-gray-600 leading-relaxed">
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase tracking-wider text-blue-600">Profil Perusahaan</h4>
                                    <div x-html="item.deskripsi"></div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase tracking-wider text-blue-600">Alamat Lengkap</h4>
                                    <p class="text-gray-700" x-text="item.alamat"></p>
                                </div>

                                
                            </div>

                        </div>
                    </template>
                </div>
            </div>

        </div>
        @else
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-200/80 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path></svg>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Tidak Ada Perusahaan Ditemukan</h3>
            <p class="text-gray-500 text-sm mb-6">Belum ada perusahaan mitra yang cocok dengan kriteria filter pencarian Anda.</p>
            <a href="{{ route('index.perusahaan') }}" class="inline-flex items-center space-x-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-all text-sm shadow-sm">
                <span>Muat Ulang Halaman</span>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection