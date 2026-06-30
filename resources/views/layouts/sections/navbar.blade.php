<div 
  x-data="{ 
    open: false, 
    dropdownMobile: false,
    dropdownDesktop: false,
    isScrolled: false 
  }"
  x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 })"
>
  
  <nav 
    :class="isScrolled 
      ? 'bg-slate-100/90 backdrop-blur-md shadow-lg border-b border-white/10 py-3 md:py-4 text-slate-800' 
      : 'bg-transparent py-5 md:py-6 border-b border-transparent text-white'"
    class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300 ease-in-out"
  >
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center transition-all duration-300">
        
        <div class="flex items-center gap-8">
          <a href="" class="flex items-center shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo CDC" 
                 class="h-10 md:h-12 w-auto object-contain transition-all duration-300 hover:scale-102">
          </a>

          <div class="hidden lg:flex items-center space-x-1 xl:space-x-2">
            <a href="{{ route('index.lowonganhome') }}" :class="isScrolled ? 'text-gray-800 hover:text-blue-600' : 'text-white hover:text-white/80'" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200">Lowongan</a>
            <a href="{{ route('index.pelatihan') }}" :class="isScrolled ? 'text-gray-800 hover:text-blue-600' : 'text-white hover:text-white/80'" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200">Pelatihan</a>
            <a href="" :class="isScrolled ? 'text-gray-800 hover:text-blue-600' : 'text-white hover:text-white/80'" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200">Tracer Study</a>
            <a href="#perusahaan" :class="isScrolled ? 'text-gray-800 hover:text-blue-600' : 'text-white hover:text-white/80'" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200">Perusahaan</a>
            <a href="#faq" :class="isScrolled ? 'text-gray-800 hover:text-blue-600' : 'text-white hover:text-white/80'" class="px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200">FAQ</a>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="hidden lg:flex items-center gap-3">
            @auth
              <!-- USER DROPDOWN (DESKTOP) -->
              <div class="relative" @click.away="dropdownDesktop = false">
                <button 
                  @click="dropdownDesktop = !dropdownDesktop" 
                  class="flex items-center gap-2 focus:outline-none bg-slate-200/60 hover:bg-slate-200/90 px-3 py-1.5 rounded-full transition duration-200"
                >
                  <img class="w-8 h-8 rounded-full object-cover" alt="Profile Picture" src="https://images.glints.com/unsafe/glints-dashboard.oss-ap-southeast-1-internal.aliyuncs.com/profile-picture-default/7.jpg">
                  <span class="text-sm font-bold tracking-wide uppercase text-slate-800">{{ Auth::user()->name }}</span>
                  <svg class="w-3 h-3 fill-current text-slate-600 transition-transform duration-200" :class="{'rotate-180': dropdownDesktop}" viewBox="0 0 100 100">
                    <polygon points="11.7984725 19 49.9935275 57.3950291 88.1885825 19 99.987055 30.7984725 49.9935275 80.792 0 30.7984725"></polygon>
                  </svg>
                </button>

                <!-- Dropdown List Desktop -->
                <div 
                  x-show="dropdownDesktop" 
                  x-transition:enter="transition ease-out duration-100"
                  x-transition:enter-start="transform opacity-0 scale-95"
                  x-transition:enter-end="transform opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-75"
                  x-transition:leave-start="transform opacity-100 scale-100"
                  x-transition:leave-end="transform opacity-0 scale-95"
                  class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden"
                  style="display: none;"
                >
                  <div class="p-2 flex flex-col space-y-0.5">
                    <a href="/id/profile" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">  
                    <svg class="w-4 h-4 fill-current text-slate-500" viewBox="0 0 100 100">
                        <path d="M61.0499573,50.7824509 C84.561913,55.0256191 87.635141,61.7303587 88.0076858,85.2561914 C88.0322374,86.7613152 88.0439796,87.0217763 88.0503844,86.8872758 L88.05,86.891 L88.0501076,87.3513854 C88.0500549,87.4104517 88.0499978,87.4721142 88.0499392,87.5364695 L88.0493168,88.7158412 C88.0493168,88.7158412 82.4504697,100 50.0262596,100 C17.6009821,100 12.0032024,88.7158412 12.0032024,88.7158412 C12.0032024,87.6654569 12.001836,86.9737404 12.000743,86.5308369 L12.0007517,86.2663026 C12.0076172,86.4505851 12.0237214,86.2928172 12.057643,84.4918873 C12.4867634,61.5841162 15.7457301,54.9797182 39.0014944,50.7824509 C39.0014944,50.7824509 42.3127669,55 50.0262596,55 C57.4642705,55 60.8078977,51.0783249 61.0372409,50.7982052 Z M50.0262596,-5.68434189e-14 C67.1996157,-5.68434189e-14 70.1586252,10.9479078 70.1586252,24.4534586 C70.1586252,37.9590094 61.1449616,48.9069171 50.0262596,48.9069171 C38.9075577,48.9069171 29.8938941,37.9590094 29.8938941,24.4534586 C29.8938941,10.9479078 32.8529035,-5.68434189e-14 50.0262596,-5.68434189e-14 Z"></path>
                      </svg>
                      Profil Saya
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">
                      <svg class="w-4 h-4 fill-current text-slate-500" viewBox="0 0 100 100">
                        <path d="M54.072 34.976h27.765L54.072 7.452v27.524zM19.096 0H59.24l30.048 30.048v60.096c0 2.725-1.001 5.048-3.004 6.971C84.28 99.038 81.917 100 79.192 100H19.096c-2.724 0-5.088-.962-7.091-2.885C10.002 95.192 9 92.87 9 90.145l.24-80.29c0-2.724.962-5.047 2.885-6.97C14.048.962 16.372 0 19.096 0z"></path>
                      </svg>
                      Lamaran Saya
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">
                      <svg class="w-4 h-4 fill-current text-slate-500" viewBox="0 0 100 100">
                        <path d="M49.306 67.548c3.036 0 5.877-.801 8.523-2.404a17.893 17.893 0 0 0 6.304-6.43c1.557-2.684 2.335-5.589 2.335-8.714s-.778-6.05-2.335-8.774c-1.556-2.724-3.658-4.888-6.304-6.49-2.646-1.603-5.487-2.404-8.523-2.404-3.035 0-5.856.801-8.464 2.404-2.607 1.602-4.69 3.766-6.246 6.49A17.397 17.397 0 0 0 32.261 50c0 3.125.778 6.03 2.335 8.714s3.639 4.828 6.246 6.43c2.608 1.603 5.429 2.404 8.464 2.404zm36.31-12.62L95.771 63.1c.467.4.74.921.818 1.562a2.904 2.904 0 0 1-.35 1.803l-9.808 17.308c-.31.56-.72.922-1.225 1.082-.506.16-1.07.12-1.693-.12l-12.025-4.928c-3.114 2.323-5.877 3.966-8.29 4.928l-1.75 13.1c-.156.642-.448 1.162-.876 1.563-.428.4-.915.601-1.46.601H39.5c-.545 0-1.032-.2-1.46-.601-.428-.4-.68-.921-.759-1.562l-1.868-13.101c-3.269-1.363-5.993-3.005-8.172-4.928l-12.142 4.928c-1.245.56-2.218.24-2.919-.962L2.374 66.466a2.904 2.904 0 0 1-.35-1.803c.077-.64.35-1.161.817-1.562l10.273-8.173c-.155-1.122-.233-2.765-.233-4.928s.078-3.806.233-4.928L2.841 36.9c-.467-.4-.74-.921-.818-1.562a2.904 2.904 0 0 1 .35-1.803l9.807-17.308c.7-1.202 1.674-1.522 2.92-.962l12.141 4.928c2.802-2.163 5.526-3.806 8.172-4.928l1.868-13.1c.078-.642.331-1.162.76-1.563.427-.4.914-.601 1.459-.601h19.613c.545 0 1.032.2 1.46.601.428.4.72.921.875 1.562L63.2 15.264a30.113 30.113 0 0 1 8.29 4.928l12.025-4.928c.622-.24 1.187-.28 1.693-.12.505.16.914.521 1.225 1.082l9.807 17.308c.312.56.428 1.162.35 1.803-.077.64-.35 1.161-.817 1.562l-10.157 8.173c.156 1.122.234 2.765.234 4.928s-.078 3.806-.234 4.928z"></path>
                      </svg>
                      Pengaturan Akun
                    </a>
                    <hr class="border-slate-100 my-1">
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-4 h-4 fill-current text-red-500" viewBox="0 0 100 100">
                        <path d="M95.115 53.734c0 25.368-20.69 46.058-46.057 46.058C23.69 99.792 3 79.102 3 53.734c0-14.573 6.717-28.006 18.411-36.762 3.418-2.579 8.216-1.92 10.735 1.5 2.579 3.357 1.859 8.215-1.5 10.734-7.796 5.877-12.293 14.813-12.293 24.528 0 16.912 13.793 30.705 30.705 30.705s30.705-13.793 30.705-30.705c0-9.715-4.498-18.651-12.294-24.528-3.359-2.519-4.078-7.377-1.5-10.735 2.52-3.418 7.377-4.078 10.735-1.5 11.695 8.757 18.411 22.19 18.411 36.763zM56.734 7.676v38.382c0 4.198-3.478 7.676-7.676 7.676s-7.677-3.478-7.677-7.676V7.676C41.381 3.478 44.86 0 49.058 0c4.198 0 7.676 3.478 7.676 7.676z"></path>
                      </svg>
                        Keluar
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @else
              <a href="{{ route('login') }}" class="text-gray-950 hover:text-blue-800 inline-flex items-center gap-1.5 font-semibold px-4 py-2 text-sm rounded-xl transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                Masuk
              </a>
              @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 text-sm rounded-xl shadow-sm hover:shadow active:scale-98 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" x2="20" y1="8" y2="14"/><line x1="23" x2="17" y1="11" y2="11"/></svg>
                Daftar Akun
                </a>
              @endif
            @endauth
          </div>

          <button 
            @click="open = !open"
            :class="isScrolled ? 'text-slate-800 hover:bg-slate-300/50' : 'text-white hover:bg-white/10'"
            class="lg:hidden p-2.5 rounded-xl focus:outline-none active:scale-95 transition-all relative z-50"
            aria-label="Toggle Menu Mobile"
          >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

      </div>
    </div>
  </nav>

  <div x-show="open" class="lg:hidden fixed inset-0 z-[100] flex justify-end" style="display: none;">
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="open = false" 
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="translate-x-full" 
         class="relative w-72 max-w-sm h-full min-h-screen bg-slate-900 border-l border-white/10 text-white flex flex-col justify-between p-6 z-10 overflow-y-auto">
      
      <div>
        <div class="flex justify-between items-center pb-4 border-b border-white/10 mb-4">
          <span class="text-base font-bold tracking-wide uppercase text-gray-300">Navigasi</span>
          <button @click="open = false" class="p-2 hover:bg-white/10 rounded-lg text-gray-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <nav class="flex flex-col space-y-1">
          <a href="#beranda" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">Beranda</a>
          <a href="#lowongan" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">Lowongan</a>
          <a href="#pelatihan" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">Pelatihan</a>
          <a href="#tracer" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">Tracer Study</a>
          <a href="#perusahaan" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">Perusahaan</a>
          <a href="#faq" @click="open = false" class="flex items-center text-gray-300 hover:text-white hover:bg-white/5 px-3 py-2.5 rounded-xl font-medium transition">FAQ</a>
        </nav>
      </div>

      <div class="border-t border-white/10 pt-4 mt-6 flex flex-col gap-2.5">
        @auth
          <a href="{{ route('dashboard') }}" @click="open = false" class="w-full text-center text-white font-semibold py-2.5 border border-white/10 bg-white/5 rounded-xl text-sm">
            Hi, {{ Auth::user()->name }}
          </a>
        @else
          <a href="{{ route('login') }}" @click="open = false" class="w-full inline-flex items-center justify-center gap-1.5 text-white bg-white/5 hover:bg-white/10 border border-white/10 font-semibold py-2.5 text-sm rounded-xl transition">
          Masuk
          </a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" @click="open = false" class="w-full inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 text-sm rounded-xl text-center shadow-sm">
              Daftar Akun
            </a>
          @endif
        @endauth
      </div>

    </div>
  </div>

</div>