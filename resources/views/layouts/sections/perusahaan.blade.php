<section id="perusahaan" class="py-20 bg-gray-50 relative overflow-hidden">
    <div class="container mx-auto px-6">
        <!-- Judul Section -->
        <div class="text-center mb-14">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-3">Perusahaan Mitra Kami</h2>
            <p class="text-gray-600 text-lg">Bersinergi dengan berbagai perusahaan terkemuka di Indonesia</p>
            <div class="mt-3 w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <!-- Grid Perusahaan -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8 place-items-center">
            @forelse($perusahaanMitra as $perusahaan)
                <a 
                    href="{{ route('show.kerjasama', $perusahaan->id) }}" 
                    class="group relative flex flex-col items-center bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 w-full h-full p-6"
                >
                    @if($perusahaan->user && $perusahaan->user->avatar)
                        @php $user = $perusahaan->user; @endphp
                        <div class="w-24 h-24 mb-4 rounded-full overflow-hidden border border-gray-200 group-hover:scale-105 transition-transform duration-300">
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 alt="{{ $perusahaan->nama_perusahaan }}" 
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                        </div>
                    @else
                        <div class="w-24 h-24 mb-4 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                            <i class="fas fa-building text-white text-3xl"></i>
                        </div>
                    @endif

                    <h3 class="text-base font-semibold text-gray-800 text-center group-hover:text-blue-600 transition-colors duration-300 line-clamp-2">
                        {{ $perusahaan->nama_perusahaan }}
                    </h3>
                </a>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-building text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada perusahaan mitra yang terdaftar</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
