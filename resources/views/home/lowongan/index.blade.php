@extends('layouts.index')
@section('title', 'Lowongan Pekerjaan')
@section('home')
<section class="py-16 bg-gradient-to-b from-blue-50 via-white to-gray-50/50 border-b border-gray-100">
    <div class="container mx-auto px-4 text-center">
        <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-700 px-4 py-1.5 text-xs font-semibold border border-blue-200/50 mb-4">
            <i class="fas fa-briefcase text-blue-500"></i> CDC IKIP PGRI Bojonegoro
        </div>
        <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-800 tracking-tight">Eksplorasi Peluang Karir</h1>
        <div class="mx-auto mt-3 h-[6px] w-24 rounded-full bg-blue-600"></div>
        <p class="text-gray-500 mt-3 text-base max-w-md mx-auto">Temukan pekerjaan impianmu dari berbagai mitra perusahaan terpercaya kami.</p>
    </div>
</section>
 
<section class="py-8 bg-gray-50/50 border-b border-gray-200/60">
    <div class="container mx-auto px-4">
        <form action="{{ route('index.lowongan') }}" method="GET" class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6">
            <div class="flex flex-col lg:flex-row items-stretch lg:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-search mr-1.5 text-blue-500"></i>Kata Kunci
                    </label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Posisi atau perusahaan..."
                        class="w-full border border-gray-200 bg-white rounded-xl py-2.5 px-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                </div>

                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-map-marker-alt mr-1.5 text-blue-500"></i>Lokasi
                    </label>
                    <div class="relative">
                        <input type="text" name="lokasi" value="{{ request('lokasi') }}"
                            placeholder="Kota atau kabupaten..."
                            class="w-full border border-gray-200 bg-white rounded-xl py-2.5 px-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition">
                    </div>
                </div>

                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-clock mr-1.5 text-blue-500"></i>Tipe Pekerjaan
                    </label>
                    <div class="relative">
                        <select name="tipe"
                            class="w-full border border-gray-200 bg-white rounded-xl py-2.5 pl-3.5 pr-10 text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30 transition cursor-pointer appearance-none">
                            <option value="">Semua Tipe</option>
                            <option value="full_time" {{ request('tipe') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ request('tipe') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="kontrak" {{ request('tipe') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang" {{ request('tipe') == 'magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row gap-3 min-w-full lg:min-w-0">
                    @if(request()->hasAny(['keyword', 'lokasi', 'tipe']))
                    <a href="{{ route('index.lowonganhome') }}"
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

<section class="py-10 bg-gray-50/30 min-h-screen" x-data="{ selectedId: null, lowongans: [] }" x-init="
    lowongans = [
        @foreach($lowonganTerbaru as $index => $lowongan)
        {
            id: {{ $lowongan->id }},
            posisi: '{{ addslashes($lowongan->posisi) }}',
            perusahaan: '{{ $lowongan->perusahaan ? addslashes($lowongan->perusahaan->nama_perusahaan) : '' }}',
            perusahaan_logo: '{{ ($lowongan->perusahaan && $lowongan->perusahaan->user && $lowongan->perusahaan->user->avatar) ? \Illuminate\Support\Facades\Storage::url($lowongan->perusahaan->user->avatar) : '' }}',
            perusahaan_deskripsi: '{{ $lowongan->perusahaan ? addslashes(Str::limit($lowongan->perusahaan->deskripsi, 180)) : '' }}',
            perusahaan_alamat: '{{ $lowongan->perusahaan ? addslashes($lowongan->perusahaan->alamat) : '' }}',
            perusahaan_telepon: '{{ $lowongan->perusahaan ? $lowongan->perusahaan->no_telepon : '' }}',
            perusahaan_email: '{{ $lowongan->perusahaan ? $lowongan->perusahaan->email : '' }}',
            tipe: '{{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}',
            pendidikan: '{{ ucfirst($lowongan->pendidikan) }}',
            category: '{{ ucfirst($lowongan->category) }}',
            lokasi: '{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}',
            tanggal_berakhir: '{{ \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->format('d F Y') }}',
            gaji: '@if($lowongan->gaji_min && $lowongan->gaji_max) Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }} @elseif($lowongan->gaji_max) Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }} @else Negosiasi @endif',
            deskripsi: `{!! nl2br(e($lowongan->deskripsi)) !!}`,
            persyaratan: `{!! nl2br(e($lowongan->persyaratan)) !!}`,
            kualifikasi: `{!! nl2br(e($lowongan->kualifikasi)) !!}`,
            jumlah_pelamar: '{{ $lowongan->kuota ?? '-' }}', 
            detail_url: '{{ route('lowongan.show', $lowongan->id) }}',
            apply_url: '{{ route('mahasiswa.lowongan.apply', $lowongan->id) }}'
        },
        @endforeach
    ];
    if(lowongans.length > 0) { selectedId = lowongans[0].id; }
">
    <div class="container mx-auto px-4">
        <div class="mb-4 flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-sm font-bold border border-blue-100">
                Total {{ $lowonganTerbaru->count() }} Formasi
            </span>
            <span class="text-sm text-gray-500">Menampilkan semua daftar formasi aktif</span>
        </div>

        @if($lowonganTerbaru->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <div class="lg:col-span-2 space-y-3 lg:max-h-[calc(100vh-14rem)] lg:overflow-y-auto pr-1 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                <template x-for="item in lowongans" :key="item.id">
                    <div>
                        <button type="button" @click="selectedId = item.id"
                            class="w-full text-left rounded-2xl border p-4 transition shadow-sm hover:shadow-md focus:outline-none"
                            :class="selectedId === item.id ? 'border-blue-600 ring-1 ring-blue-600/30 bg-blue-50/20' : 'border-gray-200 bg-white hover:border-blue-400/60'">
                            
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 shrink-0 rounded-xl bg-slate-50 border border-slate-100 p-1.5 flex items-center justify-center overflow-hidden">
                                    <template x-if="item.perusahaan_logo">
                                        <img :src="item.perusahaan_logo" :alt="item.perusahaan" class="w-full h-full object-contain">
                                    </template>
                                    <template x-if="!item.perusahaan_logo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                                    </template>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-slate-800 leading-snug line-clamp-2" x-text="item.posisi"></h3>
                                    <p class="text-xs font-semibold text-slate-500 mt-0.5 line-clamp-1" x-text="item.perusahaan"></p>
                                    
                                    <div class="mt-2 flex items-start gap-1.5 text-xs text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5 text-slate-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        <span class="line-clamp-1 leading-snug" x-text="item.lokasi"></span>
                                    </div>

                                    <div class="mt-3 flex items-center gap-2 text-[11px] flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-100/50" x-text="item.tipe"></span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-red-50 text-red-700 font-bold border border-red-100/50" x-text="item.pendidikan"></span>
                                        <span class="text-slate-500 font-medium ml-auto flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            <span x-text="item.jumlah_pelamar ? item.jumlah_pelamar + ' Kebutuhan' : '-'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </button>

                        <div class="block lg:hidden mt-2 border border-gray-200 bg-white rounded-2xl p-4 space-y-4 shadow-inner" x-show="selectedId === item.id" x-collapse>
                            <div class="grid grid-cols-2 gap-2 text-center text-xs">
                                <div class="bg-gray-50 p-2 rounded-xl">
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">Pendidikan</div>
                                    <div class="font-bold text-gray-700 mt-0.5" x-text="item.pendidikan"></div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded-xl">
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">Estimasi Gaji</div>
                                    <div class="font-bold text-emerald-600 mt-0.5" x-text="item.gaji || '-'"></div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600" x-html="item.deskripsi"></div>
                            <div class="flex gap-2">
                                <a :href="item.detail_url" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg> Detail Selengkapnya
                                </a>
                                @if(Auth::check() && Auth::user()->role == 'mahasiswa')
                                <a :href="item.apply_url" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg> Daftar Sekarang
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="hidden lg:block lg:col-span-3">
                <div class="sticky top-24 max-h-[calc(100vh-14rem)] overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-sm [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full">
                    <template x-for="item in lowongans" :key="item.id">
                        <div x-show="selectedId === item.id" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            
                            <div class="relative px-6 py-6 border-b border-gray-100 bg-gradient-to-b from-blue-50/20 to-white overflow-hidden">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-16 shrink-0 rounded-xl bg-white border border-gray-200 p-2 flex items-center justify-center shadow-sm">
                                        <template x-if="item.perusahaan_logo">
                                            <img :src="item.perusahaan_logo" :alt="item.perusahaan" class="w-full h-full object-contain">
                                        </template>
                                        <template x-if="!item.perusahaan_logo">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M8 10h.01"></path><path d="M16 10h.01"></path><path d="M8 14h.01"></path><path d="M16 14h.01"></path></svg>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h2 class="text-lg font-extrabold text-gray-800 leading-snug" x-text="item.posisi"></h2>
                                        <p class="text-sm font-bold text-blue-600 mt-1" x-text="item.perusahaan"></p>
                                        
                                        <div class="flex flex-wrap items-center gap-2 mt-2">
                                            <span class="inline-flex items-center rounded-full bg-white border border-gray-200 text-gray-600 px-2.5 py-0.5 text-[11px] font-semibold shadow-sm" x-text="'Tipe: ' + item.tipe"></span>
                                            <span class="inline-flex items-center rounded-full bg-white border border-gray-200 text-gray-600 px-2.5 py-0.5 text-[11px] font-semibold shadow-sm" x-text="'Batas: ' + item.tanggal_berakhir"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kebutuhan</div>
                                        <p class="mt-0.5 text-xs font-bold text-gray-700" x-text="item.jumlah_pelamar ? item.jumlah_pelamar + ' Orang' : '-'"></p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pendidikan</div>
                                        <p class="mt-0.5 text-xs font-bold text-gray-700" x-text="item.pendidikan ? item.pendidikan : '-' "></p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-50 text-blue-600 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M3 10h18"></path></svg></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Kategori</div>
                                        <p class="mt-0.5 text-xs font-bold text-gray-700" x-text="item.category ? item.category : '-' "></p>
                                    </div>
                                    <div class="rounded-xl bg-white border border-gray-200/80 p-3 flex flex-col items-center text-center hover:shadow-sm transition-shadow">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 mb-1"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="12" x="2" y="6" rx="2"></rect><circle cx="12" cy="12" r="2"></circle></svg></span>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Estimasi Gaji</div>
                                        <p class="mt-0.5 text-xs font-bold text-emerald-600" x-text="item.gaji || '-'"></p>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <a :href="item.detail_url" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm whitespace-nowrap">
                                    Detail Selengkapnya
                                    </a>
                                    @if(Auth::check() && Auth::user()->role == 'mahasiswa')
                                    <a :href="item.apply_url" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-sm whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg> Daftar Sekarang
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6 space-y-4 text-sm text-gray-600 leading-relaxed">
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase tracking-wider text-blue-600">Deskripsi Pekerjaan</h4>
                                    <div x-html="item.deskripsi"></div>
                                </div>
                                <template x-if="item.persyaratan">
                                    <div>
                                        <h4 class="font-bold text-gray-800 mb-1 text-xs uppercase tracking-wider text-blue-600">Persyaratan</h4>
                                        <div x-html="item.persyaratan"></div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>
                </div>
            </div>

        </div>
        @else
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-200/80 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300 mb-4"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path><rect width="20" height="14" x="2" y="6" rx="2"></rect></svg>
            <h3 class="text-xl font-bold text-gray-800 mb-1">Tidak Ada Formasi Ditemukan</h3>
            <p class="text-gray-500 text-sm mb-6">Belum ada formasi lowongan pekerjaan yang cocok dengan kriteria filter pencarian Anda.</p>
            <a href="{{ route('index.lowonganhome') }}" class="inline-flex items-center space-x-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition-all text-sm shadow-sm">
                <span>Muat Ulang Halaman</span>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection