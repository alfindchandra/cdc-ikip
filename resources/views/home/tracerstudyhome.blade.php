<section id="tracer-study" class="relative bg-white py-24 px-4 sm:px-6 lg:px-8 border-t border-slate-100 overflow-hidden">
    
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-50 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-sky-700 bg-emerald-50 border border-emerald-200 px-4 py-1.5 rounded-full inline-block">
                        Evaluasi Mutu Lulusan
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Tracer Study & <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-teal-600">Penyerapan Alumni</span>
                    </h2>
                </div>
                
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed text-justify">
                    Tracer Study merupakan instrumen penting bagi <span class="text-slate-900 font-medium">IKIP PGRI Bojonegoro</span> untuk melacak jejak karier, masa tunggu kerja, dan relevansi kurikulum akademik di dunia usaha dan dunia industri (DUDI). Kontribusi Anda sangat berharga bagi peningkatan mutu pendidikan kampus.
                </p>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
                    <span class="material-symbols-outlined text-sky-600 mt-0.5">verified_user</span>
                    <p class="text-xs text-slate-600 leading-normal">
                        Seluruh data yang diisi oleh alumni dijamin kerahasiaannya dan hanya digunakan untuk kepentingan akreditasi serta pengembangan internal kampus.
                    </p>
                </div>

                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="#" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:from-sky-500 hover:to-teal-500 shadow-lg shadow-sky-600/10 hover:shadow-sky-600/20 transition-all transform active:scale-95 group rounded-xl">
                        Isi Kuesioner Tracer
                        <span class="material-symbols-outlined text-sm font-bold group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                    <a href="" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Laporan Detail Publik
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 bg-slate-50/50 backdrop-blur-md border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-xl relative">
                
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b border-slate-200">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 tracking-wide">Ringkasan Real Data Alumni</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Metrik akumulasi data keterserapan alumni langsung dari sistem</p>
                    </div>
                </div>

                <!-- Sinkronisasi Grid Item dengan data Array $statistik dari Controller -->
                <div class="grid grid-cols-2 gap-4 sm:gap-6 pt-6">
                    <div class="bg-white border border-slate-200 p-4 rounded-xl space-y-1 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Total Responden</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-baseline gap-1">
                            <span>{{ $statistik['total_responden'] ?? 0 }}</span>
                            <span class="text-xs font-normal text-slate-400">Orang</span>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 p-4 rounded-xl space-y-1 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Alumni Bekerja</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-sky-600 tracking-tight">
                            {{ $statistik['bekerja'] ?? 0 }} <span class="text-xs font-normal text-slate-400 text-gray-900">Orang</span>
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 p-4 rounded-xl space-y-1 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Rata-rata Gaji</span>
                        <div class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                            Rp {{ number_format($statistik['rata_gaji'] ?? 0, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="bg-white border border-slate-200 p-4 rounded-xl space-y-1 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Rata-rata Omzet Usaha</span>
                        <div class="text-xl sm:text-2xl font-extrabold text-blue-600 tracking-tight">
                            Rp {{ number_format($statistik['rata_omzet'] ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Bagian Waktu Tunggu Kerja / Kesesuaian Bidang -->
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <span class="text-xs font-semibold text-slate-700 block mb-4">Grafik Kesesuaian Pekerjaan / Relevansi (%)</span>
                    <div class="space-y-3">
                        @foreach($kesesuaianBidang as $bidang)
                        <div>
                            <div class="flex justify-between text-xs text-slate-500 mb-1">
                                <span>{{ $bidang['label'] }} ({{ $bidang['jumlah'] }} Alumni)</span>
                                <span class="font-bold text-slate-800">{{ $bidang['persentase'] }}%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-sky-500 to-teal-500 rounded-full transition-all duration-500" style="width: {{ $bidang['persentase'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>