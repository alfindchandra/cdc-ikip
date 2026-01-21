<nav 
    x-data="{ open: false, dropdown: false }"
    class="fixed top-0 left-0 w-full bg-white shadow-lg z-50"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      
      <!-- Kiri: Logo & Menu Desktop -->
      <div class="flex items-center space-x-8">
        <!-- Logo -->
        <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
        </a>

        <!-- Menu Desktop -->
        <div class="hidden lg:flex items-center space-x-4">
          <a href="#lowongan" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition">Lowongan</a>
          <a href="#pelatihan" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition">Pelatihan</a>
          <a href="#tracer" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition">Tracer Study</a>
          <a href="#perusahaan" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg text-sm font-medium transition">Perusahaan</a>

          
        </div>
      </div>

      <!-- Kanan: Tombol dan Burger -->
      <div class="flex items-center space-x-3">
        <!-- Tombol Desktop -->
        <div class="hidden lg:flex items-center space-x-3">
    @auth
        <a href="{{ route('dashboard') }}" class="text-gray-700 font-semibold px-3 py-2">
            Hi, {{ Auth::user()->name }}
        </a>
    @else
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-semibold px-3 py-2 rounded-lg transition">
            Masuk
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                Register
            </a>
        @endif
    @endauth
</div>

        <!-- Tombol Burger -->
        <button 
          @click="open = !open"
          class="lg:hidden p-2 rounded-md hover:bg-gray-100 focus:outline-none"
        >
          <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Menu Mobile -->
  <div 
    x-show="open"
    x-transition
    @click.away="open = false"
    class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 flex justify-end"
  >
    <div class="w-64 h-full bg-white shadow-2xl p-6 overflow-y-auto" @click.stop>
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <span class="text-lg font-semibold text-gray-800">Menu</span>
        <button @click="open = false" class="p-2 hover:bg-gray-100 rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Menu Links -->
      <nav class="flex flex-col space-y-3">
        <a href="https://karir.itb.ac.id/company" class="text-gray-700 hover:text-blue-600 text-base font-medium">Perusahaan</a>
        <a href="https://karir.itb.ac.id/vacancy" class="text-gray-700 hover:text-blue-600 text-base font-medium">Lowongan</a>
        <a href="https://karir.itb.ac.id/kewirausahaan" class="text-gray-700 hover:text-blue-600 text-base font-medium">Entrepreneurship</a>
        <a href="https://tracer.itb.ac.id/" class="text-gray-700 hover:text-blue-600 text-base font-medium">Tracer Study</a>

        <!-- Dropdown di Mobile -->
        <div x-data="{ dropdown: false }" class="border-t pt-3 mt-3">
          <button 
            @click="dropdown = !dropdown"
            class="flex justify-between items-center w-full text-gray-700 hover:text-blue-600 text-base font-medium"
          >
            Bantuan
            <svg class="ml-2 h-5 w-5 transition-transform duration-200" :class="dropdown ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div x-show="dropdown" x-transition class="pl-4 mt-2 space-y-2">
            <a href="#" class="block text-gray-600 hover:text-blue-600 text-sm">Informasi</a>
            <a href="https://karir.itb.ac.id/faq" class="block text-gray-600 hover:text-blue-600 text-sm">F.A.Q</a>
          </div>
        </div>
      </nav>

      <!-- Tombol Aksi Mobile -->
      <div class="mt-8 flex flex-col space-y-3">
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-semibold py-2 border border-gray-200 rounded-lg transition">Masuk</>
        <a href="https://karir.itb.ac.id/registration" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 text-center rounded-lg shadow">Register</a>
        <a href="https://karir.itb.ac.id/post-a-job" class="border border-blue-600 text-blue-600 hover:bg-blue-50 font-semibold py-2 text-center rounded-lg transition">Posting Pekerjaan</a>
      </div>
    </div>
  </div>
</nav>
