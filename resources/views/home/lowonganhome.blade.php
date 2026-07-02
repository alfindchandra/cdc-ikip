<div class="w-full bg-white min-h-screen font-sans antialiased text-gray-800">
    <div class="max-w-7xl mx-auto px-4 py-6 md:py-12">
        
        <!-- Form disinkronkan dengan HTTP GET Request WelcomeController -->
        <form action="{{ url()->current() }}" method="GET" class="bg-white p-5 md:p-8 rounded-2xl border border-gray-200 shadow-sm max-w-4xl mx-auto mb-10 md:mb-16">
            <h1 class="text-xl md:text-3xl font-extrabold text-gray-900 text-center mb-6 tracking-tight">
                Temukan Loker Terbaru di Indonesia
            </h1>
            
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        name="keyword"
                        type="text" 
                        value="{{ request('keyword') }}"
                        placeholder="Cari Nama Pekerjaan, Skill, dan Perusahaan" 
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm md:text-base"
                    >
                </div>

                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <input 
                        name="lokasi"
                        type="text" 
                        value="{{ request('lokasi') }}"
                        placeholder="Semua Kota/Provinsi" 
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm md:text-base"
                    >
                </div>

                <button type="submit" class="w-full lg:w-auto bg-sky-600 hover:bg-sky-700 active:scale-[0.98] text-white font-semibold px-8 py-3 rounded-xl transition duration-200 shadow-md text-sm md:text-base">
                    Cari
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
            
            <!-- Mengambil data riil $lowonganTerbaru dari Controller -->
            <div class="lg:col-span-2">
                <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4 md:mb-6 flex items-center gap-2">
                    Lowongan Kerja Terbaru
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($lowonganTerbaru as $loker)
                        <a href="{{ route('lowongan.show', $loker->id) }}" class="group border border-gray-200 rounded-2xl p-5 bg-white hover:border-sky-500 hover:shadow-md transition-all duration-200">
                            <div class="flex flex-col justify-between h-full space-y-4">
                                <div>
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 mb-2">
                                        {{ Str::title($loker->tipe_pekerjaan ?? 'Full Time') }}
                                    </span>
                                    <h3 class="font-bold text-gray-900 group-hover:text-sky-600 transition-colors text-base line-clamp-1">
                                        {{ $loker->posisi }}
                                    </h3>
                                    <p class="text-xs text-gray-500 font-medium mt-1">
                                        {{ $loker->perusahaan->nama_perusahaan ?? 'Perusahaan Rahasia' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 pt-2 border-t border-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <span class="line-clamp-1">{{ $loker->lokasi }}</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-2 text-center py-8 text-gray-400 text-sm">
                            Tidak ada lowongan ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-8 md:space-y-10">
                <!-- Mengambil data riil $perusahaanMitra dari Controller -->
                <div>
                    <h2 class="text-lg md:text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        Mitra Perusahaan Terpercaya
                    </h2>
                    <div class="grid grid-cols-2 gap-3 items-center">
                        @foreach($perusahaanMitra as $mitra)
                            <a href="{{ route('show.perusahaan', $mitra->id) }}" class="p-3 border border-gray-200 rounded-2xl bg-white hover:border-sky-400 hover:shadow-sm transition duration-150 flex justify-center items-center h-16" title="{{ $mitra->nama_perusahaan }}">
                                @if($mitra->logo)
                                    <img src="{{ asset('storage/' . $mitra->logo) }}" alt="{{ $mitra->nama_perusahaan }}" class="max-h-10 max-w-full object-contain filter grayscale opacity-80 hover:grayscale-0 hover:opacity-100 transition duration-200">
                                @else
                                    <span class="text-xs font-bold text-gray-400 text-center line-clamp-2">{{ $mitra->nama_perusahaan }}</span>
                                @endif
                            </a>
                        @endforeach
                        
                        <a href="{{ route('index.perusahaan') }}" class="p-3 border border-dashed border-gray-300 rounded-2xl flex justify-center items-center h-16 text-xs md:text-sm font-bold text-sky-600 hover:bg-sky-50/50 hover:border-sky-400 transition duration-150">
                            Lihat Semua...
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>