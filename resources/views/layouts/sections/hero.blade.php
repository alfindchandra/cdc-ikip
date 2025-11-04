<!-- Banner Slider -->
<section 
  x-data="{
    current: 0,
    slides: [
      { url: '{{ asset('images/hero1.png') }}', link: 'https://karir.itb.ac.id/ticket-registration' },
      { url: '{{ asset('images/hero2.png') }}', link: 'https://karir.itb.ac.id/ticket-registration' },
      { url: '{{ asset('images/hero3.png') }}', link: 'https://tiket.karir.itb.ac.id' }
    ],
    interval: null,
    next() { this.current = (this.current + 1) % this.slides.length },
    prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length },
    start() {
      this.interval = setInterval(() => { this.next() }, 4000)
    },
    stop() {
      clearInterval(this.interval)
      this.interval = null
    }
  }"
  x-init="start()"
  class="relative w-full mt-16 sm:mt-20 h-[220px] sm:h-[360px] md:h-[420px] lg:h-[520px] overflow-hidden bg-gray-100"
>

  <!-- Slides -->
  <template x-for="(slide, index) in slides" :key="index">
    <a 
      :href="slide.link"
      class="absolute inset-0 transition-all duration-700 ease-in-out"
      :class="current === index ? 'opacity-100 z-10' : 'opacity-0 z-0'"
    >
      <div 
        class="w-full h-full bg-center bg-cover"
        :style="`background-image: url('${slide.url}')`"
      ></div>
    </a>
  </template>

  <!-- Overlay Gradient -->
  <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>

  <!-- Navigasi Panah -->
  <div class="absolute inset-0 flex items-center justify-between px-2 sm:px-4 z-20">
    <button 
      @click="prev(); stop(); start()"
      class="bg-white/80 hover:bg-white text-gray-700 rounded-full p-1 sm:p-2 shadow-md transition"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
    <button 
      @click="next(); stop(); start()"
      class="bg-white/80 hover:bg-white text-gray-700 rounded-full p-1 sm:p-2 shadow-md transition"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>

  <!-- Dots Indicator -->
  <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-30">
    <template x-for="(slide, index) in slides" :key="index">
      <button 
        @click="current = index; stop(); start()"
        class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all duration-300"
        :class="current === index ? 'bg-white scale-125' : 'bg-gray-400/70 hover:bg-gray-300'"
      ></button>
    </template>
  </div>
</section>
