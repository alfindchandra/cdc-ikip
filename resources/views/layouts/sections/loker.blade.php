<section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">
                    Cari Lowongan Impianmu
                </h2>
                
                <form action="{{ route('lowongan.index') }}" method="GET" class="bg-white rounded-xl shadow-lg p-6">
                    <div class="grid md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                            <input type="text" name="keyword" placeholder="Cari posisi, perusahaan..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                            <select name="lokasi" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Semua Lokasi</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Surabaya">Surabaya</option>
                                <option value="Medan">Medan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pekerjaan</label>
                            <select name="tipe" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Semua Tipe</option>
                                <option value="full_time">Full Time</option>
                                <option value="part_time">Part Time</option>
                                <option value="kontrak">Kontrak</option>
                                <option value="magang">Magang</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center justify-end mt-2 md:mt-6">
                        <button type="submit" class="gradient-primary text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition">
                        <i class="fas fa-search mr-2"></i>
                        Cari Lowongan
                        </div>
                    </button>
                    </div>
                    
                    
                </form>
            </div>
        </div>
    </section>

 
<section id="lowongan" class="py-5 bg-gray-50" x-data>
    <div class="container mx-auto px-4">
       
        <!-- Grid 2 kolom -->
        <div class="grid md:grid-cols-2 gap-8">
            @forelse($lowonganTerbaru as $lowongan)
            <a 
                href="{{ route('lowongan.show', $lowongan->id) }}" 
                x-data="{ hover: false }"
                @mouseenter="hover = true" 
                @mouseleave="hover = false"
                class="relative flex flex-col md:flex-row bg-white rounded-2xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-1"
            >
                <!-- Logo perusahaan -->
                <div class="flex items-center justify-center md:w-40 w-full bg-gray-100">
                    @if($lowongan->perusahaan->logo)
                    <div 
                        class="w-32 h-32 bg-center bg-contain bg-no-repeat" 
                        style="background-image: url('{{ asset('storage/' . $lowongan->perusahaan->logo) }}');">
                    </div>
                    @else
                    <div class="w-32 h-32 bg-gradient-to-r from-teal-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-4xl">
                        <i class="fas fa-building"></i>
                    </div>
                    @endif
                </div>

                <!-- Konten -->
                <div class="flex flex-col justify-between p-6 w-full">
                    <div>
                        <!-- Judul -->
                        <h4 class="text-lg font-semibold text-gray-800 mb-3 leading-snug line-clamp-2">
                            {{ $lowongan->judul }}
                        </h4>

                        <!-- Informasi akademik & jumlah lowongan -->
                        <div class="flex flex-wrap items-center text-sm text-gray-600 mb-3">
                            <div class="flex items-center mr-4">
                                <span class="font-medium text-gray-700 mr-1">Mencari:</span>
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-md text-xs">S1</span>
                                <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-md text-xs ml-1">S2</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-gray-400 mr-2"></i>
                                <span>{{ rand(1,3) }} Lowongan</span> <!-- contoh dinamis -->
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t pt-3 mt-2 text-sm">
                        <div class="flex justify-between items-center">
                            <div class="text-gray-700 font-medium">
                                {{ $lowongan->perusahaan->nama_perusahaan }}
                            </div>
                            <div class="text-gray-500 text-xs">
                                Last Posted {{ $lowongan->created_at->translatedFormat('d F Y | H.i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Border hover -->
                <div 
                    class="absolute inset-0 border-2 border-transparent rounded-2xl transition-colors duration-300 pointer-events-none"
                    :class="hover ? 'border-purple-400' : ''"
                ></div>
            </a>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500">Belum ada lowongan tersedia</p>
            </div>
            @endforelse
        </div>

        <!-- Tombol mobile -->
        <div class="text-center mt-10 md:hidden">
            <a href="{{ route('lowongan.index') }}" 
               class="inline-block bg-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                Lihat Semua Lowongan
            </a>
        </div>
    </div>
</section>