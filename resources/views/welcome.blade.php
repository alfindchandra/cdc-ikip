<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKIP PGRI Career Center </title>
    <link rel="icon" href="{{ asset('images/xlogo.png') }}" type="image/png">
    @vite(['resources/css/app.css'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navbar -->
    @include('layouts.sections.navbar')

    <!-- Hero Section -->
    @include('layouts.sections.hero')

  

    <!-- Search Section -->
    @include('layouts.sections.loker')





    <!-- Training Section -->
    <section id="pelatihan" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Pelatihan & Pengembangan</h2>
                <p class="text-gray-600">Tingkatkan skill-mu dengan pelatihan berkualitas</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                @forelse($pelatihanTerbaru as $pelatihan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    @if($pelatihan->thumbnail)
                    <img src="{{ asset('storage/' . $pelatihan->thumbnail) }}" alt="{{ $pelatihan->judul }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 gradient-secondary flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-white text-5xl"></i>
                    </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-xs font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                            </span>
                            @if($pelatihan->biaya > 0)
                            <span class="text-sm font-semibold text-gray-700">
                                Rp {{ number_format($pelatihan->biaya, 0, ',', '.') }}
                            </span>
                            @else
                            <span class="text-sm font-semibold text-green-600">GRATIS</span>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">
                            {{ $pelatihan->judul }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ $pelatihan->deskripsi }}
                        </p>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar w-4 mr-2"></i>
                                {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                {{ $pelatihan->tempat ?? 'Online' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users w-4 mr-2"></i>
                                {{ $pelatihan->jumlah_peserta }}/{{ $pelatihan->kuota }} Peserta
                            </div>
                        </div>
                        
                        <a href="{{ route('mahasiswa.pelatihan.show', $pelatihan->id) }}" class="block w-full text-center bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700 transition">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-chalkboard-teacher text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500">Belum ada pelatihan tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('index.tracer-study')
       @include('layouts.sections.perusahaan')

    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 gradient-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">{{ config('app.ikip') }}</h3>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 mb-4">
                        Membantu mahasiswa dan alumni meraih karir impian melalui layanan penempatan kerja dan pelatihan profesional.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-purple-600 transition">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Menu Cepat</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#beranda" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#lowongan" class="hover:text-white transition">Lowongan Kerja</a></li>
                        <li><a href="#perusahaan" class="hover:text-white transition">Perusahaan</a></li>
                        <li><a href="#pelatihan" class="hover:text-white transition">Pelatihan</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Layanan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Pelatihan</a></li>
                        <li><a href="#" class="hover:text-white transition">Job Fair</a></li>
                        <li><a href="#" class="hover:text-white transition">Lowongan Kerja</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span>Jl. Panglima Polim No. 46 Bojonegoro</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            <span>0353 886170</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>admin@ikippgribojonegoro.ac.id</span>
                        </li>
                        
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-400 mb-4 md:mb-0">
                        © 2024 CDC {{ config('app.ikip') }}. All rights reserved.
                    </p>
                    <div class="flex space-x-6 text-sm text-gray-400">
                        <a href="#" class="hover:text-white transition">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition">Terms of Service</a>
                        <a href="#" class="hover:text-white transition">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 w-12 h-12 gradient-primary rounded-full shadow-lg items-center justify-center hidden hover:opacity-90 transition z-50">
        <i class="fas fa-arrow-up text-white"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // FAQ Accordion
        document.querySelectorAll('.faq-button').forEach(button => {
            button.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('i');
                
                // Toggle current FAQ
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
                
                // Close other FAQs
                document.querySArray('.faq-content').forEach(otherContent => {
                    if (otherContent !== content && !otherContent.classList.contains('hidden')) {
                        otherContent.classList.add('hidden');
                        otherContent.previousElementSibling.querySelector('i').classList.remove('rotate-180');
                    }
                });
            });
        });

        // Scroll to Top
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('hidden');
                scrollToTopBtn.classList.add('flex');
            } else {
                scrollToTopBtn.classList.add('hidden');
                scrollToTopBtn.classList.remove('flex');
            }
        });

        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Close mobile menu if open
                    document.getElementById('mobile-menu').classList.add('hidden');
                }
            });
        });

        // Animation on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-hover').forEach(card => {
            observer.observe(card);
        });
    </script>
    
</body>
</html>