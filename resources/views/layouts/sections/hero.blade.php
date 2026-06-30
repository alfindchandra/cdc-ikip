<section id="home" class="relative z-10 pt-16 md:pt-24 lg:pt-28 overflow-hidden min-h-[90vh] flex items-center" style="background: linear-gradient(135deg, #0f5c84 0%, #177ba1 35%, #1e8cb8 65%, #167ba3 100%);">
  
  <img src="{{ asset('images/indo_2.svg') }}" alt="" aria-hidden="true" draggable="false" loading="eager" decoding="async" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none select-none opacity-20">
  
  <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/15 pointer-events-none"></div>
  <div class="absolute inset-0 bg-gradient-to-r from-black/20 via-transparent to-black/20 pointer-events-none"></div>

  <div class="absolute top-1/2 right-10 -translate-y-1/2 hidden xl:block pointer-events-none">
    <div class="rounded-full opacity-30 blur-3xl bg-white/10" style="width: 600px; height: 600px;"></div>
    <div class="absolute top-1/4 left-1/4 rounded-full opacity-50 blur-2xl bg-sky-200/20" style="width: 300px; height: 300px;"></div>
  </div>

  <div class="container mx-auto px-4 md:px-8 relative z-10 w-full">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-12 py-12 lg:py-0">
      
    
      <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left dynamic-fade-in">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight text-white drop-shadow-md">
          Sistem Informasi <br class="hidden sm:inline">
          <span class="text-sky-300">Career Development Center</span>
        </h1>
        <p class="mt-6 max-w-xl text-base sm:text-lg font-normal text-white/90 leading-relaxed drop-shadow-sm">
          Temukan peluang kerja terbaik, kembangkan potensi dirimu, dan bangun karir impian bersama ribuan mitra perusahaan terpercaya. Bersiaplah melangkah lebih jauh hari ini!
        </p>
        <div class="mt-8 flex flex-wrap gap-4 justify-center lg:justify-start w-full">
          <a href="#lowongan" class="px-6 py-3 bg-white text-[#1270a0] font-bold rounded-xl shadow-lg hover:bg-sky-100 transition-all duration-300 transform hover:-translate-y-0.5">
            Jelajahi Lowongan
          </a>
          <a href="#Panduan" class="px-6 py-3 bg-transparent text-white font-semibold rounded-xl border border-white/40 hover:bg-white/10 transition-all duration-300">
            Panduan Karir
          </a>
        </div>

      </div>
      <div class="hidden md:flex w-full lg:w-1/2 items-end justify-center lg:justify-end self-end mt-6 lg:mt-0">
        <img src="{{ asset('images/mbkikip.png') }}" alt="Peserta seleksi karir" draggable="false" loading="eager" decoding="async" class="max-h-[350px] md:max-h-[450px] xl:max-h-[520px] w-auto object-contain object-bottom select-none filter drop-shadow-2xl">
      </div>

    </div>
  </div>
</section>

<style>
  @keyframes customFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .dynamic-fade-in {
    animation: customFadeIn 0.8s ease-out forwards;
  }
</style>