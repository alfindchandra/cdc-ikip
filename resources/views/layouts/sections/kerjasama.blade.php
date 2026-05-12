<!-- Section Bentuk Kerjasama -->
<section id="kerjasama" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-3">Bentuk Kerjasama</h2>
            <p class="text-gray-600 text-lg">Lihat berbagai bentuk kerjasama strategis dengan industri</p>
            <div class="mt-4 w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <!-- Grid Kerjasama -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kerjasamaTerbaru as $kerjasama)
            <div
                class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 card-hover">
                <!-- Badge Tipe Kerjasama -->
                <div class="relative h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                <div class="p-6">
                    <!-- Jenis & Status -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                            {{ ucfirst(str_replace('_', ' ', $kerjasama->jenis_kerjasama)) }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                            {{ ucfirst($kerjasama->status) }}
                        </span>
                    </div>

                    <!-- Judul Kerjasama -->
                    <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                        {{ $kerjasama->judul }}
                    </h3>

                    <!-- Perusahaan -->
                    @if($kerjasama->perusahaan)
                    <div class="flex items-center mb-3 text-sm text-gray-600">
                        <i class="fas fa-building text-blue-500 mr-2"></i>
                        <span class="font-semibold">{{ $kerjasama->perusahaan->nama_perusahaan }}</span>
                    </div>
                    @endif

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ $kerjasama->deskripsi }}
                    </p>

                    <!-- Info Detail -->
                    <div class="space-y-2 mb-4 border-t border-gray-200 pt-4">
                        <!-- Tanggal Mulai -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-start text-blue-500 w-4 mr-3"></i>
                            <span>Mulai: {{ \Carbon\Carbon::parse($kerjasama->tanggal_mulai)->format('d M Y') }}</span>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar-end text-blue-500 w-4 mr-3"></i>
                            <span>Berakhir:
                                {{ \Carbon\Carbon::parse($kerjasama->tanggal_berakhir)->format('d M Y') }}</span>
                        </div>

                        <!-- Nilai Kontrak -->
                        @if($kerjasama->nilai_kontrak)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-money-bill text-green-500 w-4 mr-3"></i>
                            <span>Nilai Kontrak: Rp {{ number_format($kerjasama->nilai_kontrak, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>


                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-handshake text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada bentuk kerjasama yang tersedia</p>
            </div>
            @endforelse
        </div>

        <!-- CTA Button -->
        @if($kerjasamaTerbaru->count() > 0)
        <div class="text-center mt-12">
            <a href="{{ route('index.kerjasama') }}"
                class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                <span>Lihat Semua Bentuk Kerjasama</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</section>