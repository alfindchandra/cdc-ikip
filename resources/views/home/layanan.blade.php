<div>


    <section class="relative min-h-screen bg-cover bg-center bg-no-repeat py-20 px-4 sm:px-6 lg:px-8 flex items-center bg-slate-50"
             style="background-image: url('https://ikippgribojonegoro.ac.id/wp-content/uploads/2025/03/1921-x-634.jpg');">
        

        <div class="relative max-w-7xl mx-auto z-10 w-full" 
             x-data="{ 
                subJudul: 'Pusat Karir & Penyelarasan Lulusan',
                judul: 'Layanan Career Center',
                kampus: 'IKIP PGRI Bojonegoro',
                activeModal: null,
                layananList: [
                    {
                        icon: 'school',
                        judul: 'Konseling Karir & Akademik',
                        deskripsi: 'Bimbingan intensif bagi mahasiswa dan alumni untuk memetakan potensi diri, merancang jalur karir kependidikan maupun non-kependidikan, serta kesiapan mental memasuki dunia kerja.',
                        detail: 'Layanan konsultasi tatap muka maupun daring bersama psikolog dan konselor karir berpengalaman. Membantu Anda menyusun CV profesional, simulasi interview, hingga mengatasi kecemasan pra-kerja.'
                    },
                    {
                        icon: 'work',
                        judul: 'Bursa Kerja & Magang',
                        deskripsi: 'Akses eksklusif informasi lowongan pendidik, tenaga kependidikan, serta magang industri. Menghubungkan Anda langsung dengan jaringan sekolah dan korporasi mitra.',
                        detail: 'Kami bekerja sama dengan ratusan Lembaga Pendidikan, Sekolah Negeri/Swasta, dan Perusahaan Nasional untuk menyalurkan lulusan terbaik melalui sistem rekrutmen terpadu.'
                    },
                    {
                        icon: 'handshake',
                        judul: 'Kerja Sama Dengan Perusahaan',
                        deskripsi: 'Fasilitas rekrutmen kampus (on-campus recruitment), company branding, psikotes massal, dan pemenuhan kebutuhan SDM unggul bagi sekolah maupun instansi mitra.',
                        detail: 'Menjembatani dunia industri dengan kampus dalam bentuk bursa kerja (Job Fair) periodik, rekrutmen langsung di kampus, kerja sama kurikulum, serta penyelarasan kompetensi lulusan.'
                    },
                    {
                        icon: 'analytics',
                        judul: 'Tracer Study & Alumni',
                        deskripsi: 'Sistem pelacakan digital kelulusan alumni guna mengevaluasi relevansi kurikulum, serta mengukur indeks kepuasan pengguna lulusan secara berkala dan akurat.',
                        detail: 'Pengumpulan data berkala untuk mengukur masa tunggu lulusan, kesesuaian bidang kerja, dan umpan balik pengguna lulusan guna akreditasi dan peningkatan mutu akademik kampus.'
                    },
                    {
                        icon: 'workspace_premium',
                        judul: 'Pelatihan & Sertifikasi',
                        deskripsi: 'Peningkatan kompetensi melalui workshop microteaching modern, pembuatan media ajar digital berbasis AI, TOEFL preparation, hingga sertifikasi keahlian khusus.',
                        detail: 'Program pelatihan akselerasi karir bersertifikat resmi guna membekali lulusan dengan hard-skill dan soft-skill tambahan di luar kurikulum reguler.'
                    },
                    {
                        icon: 'lightbulb',
                        judul: 'Temukan Karir Impianmu',
                        deskripsi: 'Fasilitas Karir yang membuat kamu lulus langsung bekerja di perusahaan terbaik. Kami membantu kamu menemukan karir impianmu.',
                        detail: 'Bersama kami, kamu akan mendapatkan bimbingan karir, pelatihan, dan akses ke lowongan pekerjaan yang sesuai dengan minat dan bakatmu. Temukan karir impianmu sekarang!'
                    }
                ]
             }">
            
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 py-5">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-blue-600 bg-blue-50 border border-blue-200 px-4 py-1.5 rounded-full" x-text="subJudul"></span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    <span x-text="judul"></span> <br class="hidden sm:inline"> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 via-blue-500 to-green-600" x-text="kampus"></span>
                </h2>
                <div class="w-20 h-1 bg-gradient-to-r from-sky-600 to-blue-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <template x-for="(item, index) in layananList" :key="index">
                    
                    <div @click="activeModal = item" 
                         class="cursor-pointer group relative bg-white/80 backdrop-blur-md border border-slate-200 rounded-2xl p-6 sm:p-8 transition-all duration-300 hover:border-sky-400 hover:bg-white flex flex-col justify-between shadow-md hover:shadow-xl hover:shadow-sky-500/5 hover:-translate-y-1"
                         x-data="{ shown: false }" 
                         x-init="setTimeout(() => shown = true, index * 50)"
                         x-show="shown"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-8"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        
                        <div>
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 text-sky-600 border border-blue-100 mb-6 group-hover:from-blue-600 group-hover:to-indigo-600 group-hover:text-white group-hover:scale-110 transition-all duration-300 shadow-sm">
                                <span class="material-symbols-outlined text-2xl" x-text="item.icon"></span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-slate-800 mb-3 tracking-wide group-hover:text-sky-600 transition-colors duration-200" x-text="item.judul"></h3>
                            
                            <p class="text-slate-600 text-sm leading-relaxed text-justify group-hover:text-slate-700 transition-colors duration-200 line-clamp-4" x-text="item.deskripsi"></p>
                        </div>
                        
                       
                        
                    </div>
                </template>
            </div>

            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
                 x-show="activeModal !== null"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak>
                 
                <div class="bg-white border border-slate-100 rounded-2xl max-w-xl w-full p-6 sm:p-8 relative shadow-2xl"
                    @click.away="activeModal = null"
                    x-show="activeModal !== null"
                    x-transition:enter="transition ease-out duration-300 transform scale-95"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform scale-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95">
                    
                    <div class="absolute top-4 right-4 flex justify-end z-10">
                        <button @click="activeModal = null" class="text-slate-400 hover:text-slate-600 bg-slate-100 hover:bg-slate-200 p-1.5 rounded-full transition-colors">
                            <span class="material-symbols-outlined text-xl block">close</span>
                        </button>
                    </div>

                    <div class="flex items-start gap-4 mb-5">
                        <div class="flex-shrink-0 inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                            <span class="material-symbols-outlined text-2xl" x-text="activeModal?.icon"></span>
                        </div>
                        <div class="pr-8">
                            <h4 class="text-2xl font-bold text-slate-900 tracking-wide" x-text="activeModal?.judul"></h4>
                            <p class="text-xs font-bold uppercase text-blue-600 mt-0.5" x-text="kampus"></p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 text-slate-600 text-sm leading-relaxed text-justify">
                        <p class="font-medium text-slate-800" x-text="activeModal?.deskripsi"></p>
                        <p class="text-slate-600 border-l-2 border-blue-600 pl-3 bg-slate-50 py-2.5 px-3 rounded-r-lg" x-text="activeModal?.detail"></p>
                    </div>

                    <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end">
                        <button @click="activeModal = null" class="px-5 py-2 text-sm font-semibold rounded-xl bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/10 transition-all active:scale-95">
                            Selesai Membaca
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
</div>