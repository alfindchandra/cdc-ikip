<!-- Section Pencarian -->
<section class="py-20 bg-gradient-to-br from-purple-50 via-white to-blue-50">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Temukan Karir Impianmu
                </h2>
                <p class="text-gray-600 text-lg">
                    Cari dari ribuan lowongan kerja yang tersedia
                </p>
            </div>
            
            <!-- Form Pencarian -->
            <form action="{{ route('welcome') }}" method="GET" class="bg-white rounded-2xl shadow-xl p-8 backdrop-blur-sm">
                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <!-- Kata Kunci -->
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-purple-600"></i>Kata Kunci
                        </label>
                        <input 
                            type="text" 
                            name="keyword" 
                            value="{{ request('keyword') }}"
                            placeholder="Contoh: Web Developer" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 placeholder:text-gray-400"
                        >
                    </div>
                    
                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Lokasi
                        </label>
                        <select 
                            name="lokasi" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 appearance-none bg-white cursor-pointer"
                        >
                            <option value="">Semua Lokasi</option>
                            <option value="Jakarta" {{ request('lokasi') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="Bandung" {{ request('lokasi') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="Surabaya" {{ request('lokasi') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="Medan" {{ request('lokasi') == 'Medan' ? 'selected' : '' }}>Medan</option>
                            <option value="Semarang" {{ request('lokasi') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                            <option value="Yogyakarta" {{ request('lokasi') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                        </select>
                    </div>
                    
                    <!-- Tipe Pekerjaan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-purple-600"></i>Tipe Pekerjaan
                        </label>
                        <select 
                            name="tipe" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 appearance-none bg-white cursor-pointer"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="full_time" {{ request('tipe') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ request('tipe') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="kontrak" {{ request('tipe') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang" {{ request('tipe') == 'magang' ? 'selected' : '' }}>Magang</option>
                        </select>
                    </div>
                </div>
                
                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-4 px-8 rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-search"></i>
                        <span>Cari Lowongan</span>
                    </button>
                    
                    @if(request()->hasAny(['keyword', 'lokasi', 'tipe']))
                    <a 
                        href="{{ route('welcome') }}" 
                        class="sm:w-auto bg-gray-200 text-gray-700 py-4 px-6 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </a>
                    @endif
                </div>
                
                
            </form>
        </div>
    </div>
</section>

<!-- Section Daftar Lowongan -->
<section id="lowongan" class="py-16 bg-gray-50" x-data>
    <div class="container mx-auto px-4">
        <!-- Header Section -->
       
       
        <!-- Grid 2 kolom -->
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($lowonganTerbaru as $lowongan)
            <a 
                href="{{ route('mahasiswa.lowongan.show', $lowongan->id) }}" 
                x-data="{ hover: false }"
                @mouseenter="hover = true" 
                @mouseleave="hover = false"
                class="group relative bg-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
            >
                <!-- Konten -->
                <div class="p-6">
                    <!-- Header Card -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="mb-2 justify-between flex items-center">
                            <h4 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                {{ $lowongan->posisi }}
                            </h4>
                            <p class="text-sm text-gray-500">
                            <i class="far fa-calendar mr-1"></i>
                            {{ $lowongan->created_at->diffForHumans() }}
                            </p>
                            </div>
                            <p class="text-purple-600 font-semibold flex items-center">
                                <i class="fas fa-building mr-2"></i>
                                {{ $lowongan->perusahaan->nama_perusahaan }}
                            </p>
                            <span class="text-sm font-semibold text-green-600">
                                @if($lowongan->gaji_min && $lowongan->gaji_max)
                                    Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                @elseif($lowongan->gaji_max)
                                    Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                @else
                                    Negosiasi
                                @endif
                            </span>

                        </div>
                        
                        
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <!-- Lokasi -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                            <span>{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</span>
                        </div>
                        
                        <!-- Tipe -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                            <span>{{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan ?? 'Tidak Dicantumkan')) }}</span>
                        </div>
                        
                        <!-- Pendidikan -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-graduation-cap text-gray-400 mr-2"></i>
                            <span>Min. {{ $lowongan->pendidikan ?? 'Tidak Diketahui' }}</span>
                        </div>
                        
                        <!-- Jumlah Lowongan -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users text-gray-400 mr-2"></i>
                            <span>{{ $lowongan->kuota ?? 'Unlimited'  }} Posisi</span>
                        </div>
                    </div>

                    
                </div>

                <!-- Border Animasi -->
                <div 
                    class="absolute inset-0 border-2 border-transparent rounded-2xl transition-colors duration-300 pointer-events-none"
                    :class="hover ? 'border-purple-400' : ''"
                ></div>
                
                <!-- Glow Effect -->
                <div 
                    class="absolute inset-0 bg-gradient-to-r from-purple-400/0 via-purple-400/5 to-blue-400/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                ></div>
            </a>
            @empty
            <div class="col-span-full text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Lowongan</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['keyword', 'lokasi', 'tipe']))
                        Coba ubah filter pencarian Anda
                    @else
                        Belum ada lowongan tersedia saat ini
                    @endif
                </p>
                @if(request()->hasAny(['keyword', 'lokasi', 'tipe']))
                <a href="{{ route('welcome') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Reset Pencarian
                </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Tombol Mobile -->
        <div class="text-center mt-12 ">
            <a href="{{ route('index.lowongan') }}" 
               class="inline-flex items-center space-x-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-8 py-4 rounded-xl font-semibold hover:shadow-lg transition-all">
                <span>Lihat Semua Lowongan</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>