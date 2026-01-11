@extends('layouts.index')

@section('title', 'Tracer Study Alumni')

@section('content')

@php
    // Inisialisasi data statistik dengan nilai default untuk mencegah error jika data kosong
    $statistik = $statistik ?? [
        'total_responden' => 0,
        'bekerja' => 0,
        'kuliah' => 0,
        'wirausaha' => 0,
        'belum_bekerja' => 0,
        'rata_gaji' => 0,
        'rata_omzet' => 0,
        'tunggu_3' => 0,
        'tunggu_6' => 0,
        'tunggu_12' => 0,
        'tunggu_lebih' => 0,
    ];

    // Hitung total responden untuk persentase
    $totalResponden = $statistik['total_responden'] > 0 ? $statistik['total_responden'] : 1;

    // Pastikan 'belum_bekerja' dihitung jika tidak ada
    $statistik['belum_bekerja'] = $statistik['total_responden'] - ($statistik['bekerja'] + $statistik['kuliah'] + $statistik['wirausaha']);

    // Data untuk Chart.js Status
    $chartStatusData = [
        'bekerja' => $statistik['bekerja'],
        'kuliah' => $statistik['kuliah'],
        'wirausaha' => $statistik['wirausaha'],
        'belum_bekerja' => $statistik['belum_bekerja'],
    ];

    // Data untuk Chart.js Waktu Tunggu
    $chartWaktuTungguData = [
        'tunggu_3' => $statistik['tunggu_3'],
        'tunggu_6' => $statistik['tunggu_6'],
        'tunggu_12' => $statistik['tunggu_12'],
        'tunggu_lebih' => $statistik['tunggu_lebih'],
    ];

    // Tahun saat ini untuk tampilan
    $currentYear = date('Y');
@endphp

<div> {{-- Kontainer utama --}}

    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-indigo-700 to-purple-800 text-white py-16 sm:py-24 shadow-2xl">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-3/5">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
                        Tracer Study <span class="block text-purple-300">Alumni</span>
                    </h1>
                    <p class="text-xl opacity-90 mb-8 max-w-xl">
                        Laporan Penelusuran Alumni untuk mengetahui perkembangan karir dan kesuksesan lulusan kami.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#statistik" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl shadow-xl text-indigo-800 bg-white hover:bg-gray-100 transition duration-500 transform hover:scale-105 hover:shadow-2xl">
                            <i class="fas fa-chart-line mr-3"></i> Lihat Statistik
                        </a>
                        @auth
                            @if(auth()->user()->isSiswa())
                                <a href="" class="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-xl text-white hover:bg-white hover:text-indigo-700 transition duration-500 transform hover:scale-105">
                                    <i class="fas fa-edit mr-3"></i> Isi Kuesioner
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="lg:w-2/5 hidden lg:flex justify-end mt-10 lg:mt-0">
                    <i class="fas fa-graduation-cap text-[16rem] text-indigo-300 opacity-30"></i>
                </div>
            </div>
        </div>
    </section>

    ---

    {{-- Statistik Overview --}}
    <section id="statistik" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-indigo-600 font-semibold uppercase tracking-wider">Data Karir</span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Statistik Alumni</h2>
                <p class="text-gray-600 mt-4 text-lg">Data berdasarkan tracer study tahun <span class="font-bold text-indigo-700">{{ $currentYear }}</span></p>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                @php
                    $statCards = [
                        ['label' => 'Total Responden', 'key' => 'total_responden', 'icon' => 'fas fa-users', 'color' => 'indigo'],
                        ['label' => 'Sudah Bekerja', 'key' => 'bekerja', 'icon' => 'fas fa-briefcase', 'color' => 'green'],
                        ['label' => 'Melanjutkan Kuliah', 'key' => 'kuliah', 'icon' => 'fas fa-graduation-cap', 'color' => 'cyan'],
                        ['label' => 'Wirausaha', 'key' => 'wirausaha', 'icon' => 'fas fa-store', 'color' => 'amber'],
                    ];
                @endphp

                @foreach($statCards as $card)
                    <div class="bg-white p-6 rounded-xl shadow-xl border-b-4 border-{{ $card['color'] }}-500 transition duration-300 transform hover:shadow-2xl hover:scale-[1.02] relative overflow-hidden">
                        <i class="{{ $card['icon'] }} text-{{ $card['color'] }}-100 absolute -top-4 -right-4 text-7xl opacity-5"></i>
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-full bg-{{ $card['color'] }}-100">
                                <i class="{{ $card['icon'] }} fa-2x text-{{ $card['color'] }}-600"></i>
                            </div>
                            @if(in_array($card['key'], ['bekerja', 'kuliah', 'wirausaha']))
                                <div class="px-3 py-1 rounded-full bg-{{ $card['color'] }}-200 text-{{ $card['color'] }}-800 font-bold text-sm">
                                    {{ $totalResponden > 1 ? round(($statistik[$card['key']] ?? 0 / $totalResponden) * 100, 1) : 0 }}%
                                </div>
                            @endif
                        </div>
                        <h3 class="text-4xl font-extrabold text-gray-900 mb-1">{{ $statistik[$card['key']] ?? 0 }}</h3>
                        <p class="text-gray-500">{{ $card['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Chart Visualization --}}
             <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Status Alumni Chart (Doughnut) --}}
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-bold text-indigo-700">Status Alumni <i class="fas fa-chart-pie ml-2 text-indigo-400"></i></h2>
            </div>
            <div class="p-6 h-96 flex items-center justify-center"> {{-- Menambahkan flex dan tinggi tetap --}}
                <canvas id="statusChart" class="max-h-full"></canvas>
            </div>
        </div>

        {{-- Breakdown Status (Progress Bars) --}}
        <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-bold text-indigo-700">Persentase Status <i class="fas fa-list-alt ml-2 text-indigo-400"></i></h2>
            </div>
            <div class="p-6 space-y-6">
                @php
                    $totalResponden = $statistik['total_responden'] > 0 ? $statistik['total_responden'] : 1;
                    $breakdownStatus = [
                        'bekerja' => ['icon' => 'fas fa-briefcase', 'color' => 'green', 'label' => 'Bekerja'],
                        'wirausaha' => ['icon' => 'fas fa-store', 'color' => 'blue', 'label' => 'Wirausaha'],
                        'melanjutkan_studi' => ['icon' => 'fas fa-book', 'color' => 'cyan', 'label' => 'Melanjutkan Studi'],
                        'belum_bekerja' => ['icon' => 'fas fa-user-clock', 'color' => 'amber', 'label' => 'Belum Bekerja'],
                    ];
                @endphp

                @foreach($breakdownStatus as $key => $info)
                    @php
                        $count = $statistik[$key] ?? 0;
                        $percentage = ($count / $totalResponden) * 100;
                    @endphp
                    <div class="space-y-1 group">
                        <div class="flex justify-between text-sm font-semibold text-gray-700">
                            <span class="flex items-center text-{{ $info['color'] }}-600 transition duration-300">
                                <i class="{{ $info['icon'] }} mr-2"></i>{{ $info['label'] }}
                            </span>
                            <strong class="text-gray-900">{{ $count }} <span class="text-sm font-normal text-gray-500 ml-1">({{ round($percentage, 1) }}%)</span></strong>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-{{ $info['color'] }}-500 h-3 rounded-full transition-all duration-700 ease-out" 
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
        </div>
    </section>

    ---

    {{-- Kesesuaian Bidang --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-green-600 font-semibold uppercase tracking-wider">Kualitas Lulusan</span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Kesesuaian Bidang Kerja</h2>
                <p class="text-gray-600 mt-4 text-lg">Tingkat kesesuaian antara bidang studi dengan pekerjaan</p>
            </div>

            <div class="flex justify-center">
                <div class="w-full lg:w-4/5 xl:w-3/5 bg-gray-50 rounded-2xl shadow-inner p-8 border border-gray-200">
                    @php
                        // Mapping Bootstrap colors to Tailwind colors
                        $colorMap = [
                            'bg-success' => 'bg-green-600',
                            'bg-primary' => 'bg-indigo-600',
                            'bg-warning' => 'bg-amber-500',
                            'bg-danger' => 'bg-red-500',
                        ];
                    @endphp

                    @foreach($kesesuaianBidang as $item)
                    <div class="mb-6 last:mb-0">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-gray-700">{{ $item['label'] }}</span>
                            <span class="text-sm text-gray-500">
                                <span class="font-bold text-indigo-600">{{ $item['persentase'] }}%</span> ({{ $item['jumlah'] }} Alumni)
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-5 overflow-hidden">
                            <div class="{{ $colorMap[$item['color']] ?? 'bg-indigo-500' }} h-5 rounded-full transition-all duration-1000 ease-out flex items-center justify-end pr-3 text-white text-xs font-bold"
                                 style="width: {{ $item['persentase'] }}%">
                                {{ $item['persentase'] }}%
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    ---

    {{-- Rata-rata Gaji --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-amber-600 font-semibold uppercase tracking-wider">Potensi Penghasilan</span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Rata-rata Pendapatan Alumni</h2>
                <p class="text-gray-600 mt-4 text-lg">Berdasarkan status pekerjaan saat ini</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Rata-rata Gaji (Bekerja) --}}
                <div class="bg-white rounded-2xl shadow-2xl p-10 transition duration-500 transform hover:scale-[1.03] border-l-8 border-green-500">
                    <div class="text-center">
                        <i class="fas fa-money-bill-wave fa-5x text-green-500 mb-4"></i>
                        <h5 class="text-2xl font-bold text-gray-800 mb-2">Rata-rata Gaji (Bekerja)</h5>
                        <h3 class="text-5xl text-green-600 font-extrabold mb-0">
                            Rp {{ number_format($statistik['rata_gaji'] ?? 0, 0, ',', '.') }}
                        </h3>
                        <p class="text-gray-500 text-base mt-2">Per bulan</p>
                    </div>
                </div>

                {{-- Rata-rata Omzet (Wirausaha) --}}
                <div class="bg-white rounded-2xl shadow-2xl p-10 transition duration-500 transform hover:scale-[1.03] border-l-8 border-amber-500">
                    <div class="text-center">
                        <i class="fas fa-chart-line fa-5x text-amber-500 mb-4"></i>
                        <h5 class="text-2xl font-bold text-gray-800 mb-2">Rata-rata Omzet (Wirausaha)</h5>
                        <h3 class="text-5xl text-amber-600 font-extrabold mb-0">
                            Rp {{ number_format($statistik['rata_omzet'] ?? 0, 0, ',', '.') }}
                        </h3>
                        <p class="text-gray-500 text-base mt-2">Per bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    ---

    {{-- Top Companies --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-semibold uppercase tracking-wider">Mitra Karir</span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Perusahaan Tempat Bekerja</h2>
                <p class="text-gray-600 mt-4 text-lg">10 Perusahaan dengan jumlah alumni terbanyak</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($topCompanies as $index => $company)
                <div class="bg-white p-5 rounded-xl shadow-lg border-l-6 border-indigo-500 hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-indigo-600 text-white font-extrabold text-2xl flex items-center justify-center mr-5 shadow-lg">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-grow">
                            <h6 class="text-xl font-bold text-gray-900 truncate">{{ $company->nama_perusahaan }}</h6>
                            <small class="text-gray-500"><i class="fas fa-user-tie mr-1"></i> {{ $company->total_alumni }} Alumni</small>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800 shadow-sm">
                                {{ $company->bidang_pekerjaan }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    ---

    {{-- Testimoni Alumni --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold uppercase tracking-wider">Umpan Balik</span>
                <h2 class="text-4xl font-extrabold text-gray-900 mt-2">Testimoni Alumni</h2>
                <p class="text-gray-600 mt-4 text-lg">Pengalaman dan masukan berharga dari para lulusan</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimoni as $item)
                <div class="bg-white p-6 rounded-2xl shadow-xl border-t-8 border-purple-500 flex flex-col h-full hover:shadow-2xl transition duration-300">

                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-purple-600 text-white flex items-center justify-center mr-4 shadow-md">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                        <div>
                            <h6 class="text-xl font-bold text-gray-900 mb-0">{{ $item->siswa->user->name ?? 'Anonim' }}</h6>
                            <small class="text-gray-500">{{ $item->siswa->programStudi->nama ?? 'Program Studi' }}</small>
                        </div>
                    </div>

                    <div class="mb-4 flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $item->kepuasan_alumni ? 'text-amber-400' : 'text-gray-300' }} mr-1 text-lg"></i>
                        @endfor
                        <span class="text-base font-semibold text-gray-700 ml-2">({{ $item->kepuasan_alumni ?? '0' }}/5)</span>
                    </div>

                    <blockquote class="text-gray-700 italic border-l-4 border-purple-200 pl-4 py-3 mb-4 flex-grow relative">
                        <i class="fas fa-quote-left text-purple-300 absolute -top-1 left-2 text-2xl"></i>
                        {{ Str::limit($item->saran_pembelajaran ?? $item->saran_kurikulum ?? 'Pengalaman yang sangat berharga dan membantu dalam karir.', 150) }}
                    </blockquote>

                    <div class="border-t pt-4 mt-auto">
                        <p class="text-gray-600 text-sm">
                            <i class="fas fa-briefcase mr-2 text-purple-500"></i>
                            @if(($item->status_kerja ?? 'belum_bekerja') == 'bekerja')
                                {{ $item->jabatan ?? '-' }} di {{ $item->nama_perusahaan ?? 'Perusahaan' }}
                            @elseif(($item->status_kerja ?? 'belum_bekerja') == 'kuliah')
                                Kuliah {{ $item->jenjang ?? '-' }} di {{ $item->nama_kampus ?? 'Institusi Studi' }}
                            @elseif(($item->status_kerja ?? 'belum_bekerja') == 'wirausaha')
                                Owner - {{ $item->nama_usaha ?? 'Usaha Mandiri' }}
                            @else
                                Belum Bekerja
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    ---

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-indigo-700 to-purple-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-extrabold mb-4">Anda Alumni Kami?</h2>
            <p class="text-xl opacity-90 mb-8 max-w-3xl mx-auto">
                Bantu kami meningkatkan kualitas pendidikan dengan mengisi kuesioner tracer study. Partisipasi Anda sangat berharga!
            </p>
            @auth
                @if(auth()->user()->isSiswa())
                    <a href="" class="inline-flex items-center justify-center px-12 py-4 border border-transparent text-lg font-bold rounded-xl shadow-2xl text-indigo-700 bg-white hover:bg-gray-100 transition duration-500 transform hover:scale-110">
                        <i class="fas fa-edit mr-3"></i> Isi Kuesioner Sekarang
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-12 py-4 border border-transparent text-lg font-bold rounded-xl shadow-2xl text-indigo-700 bg-white hover:bg-gray-100 transition duration-500 transform hover:scale-110">
                    <i class="fas fa-sign-in-alt mr-3"></i> Login untuk Mengisi
                </a>
            @endauth
        </div>
    </section>

</div> {{-- End kontainer utama --}}

@push('scripts')
{{-- Hanya Chart.js yang diperlukan --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initStatusChart();
        initWaktuTungguChart();
    });

    /**
     * Inisialisasi Doughnut Chart Status Alumni
     */
    function initStatusChart() {
        const statusCtx = document.getElementById('statusChart');
        if (!statusCtx) return;

        try {
            const chartDataAttr = statusCtx.getAttribute('data-chart-data');
            const data = JSON.parse(chartDataAttr);

            if (!data || Object.values(data).every(v => v === 0)) {
                return; // Tidak perlu inisialisasi jika data kosong
            }

            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Bekerja', 'Kuliah', 'Wirausaha', 'Belum Bekerja'],
                    datasets: [{
                        data: [
                            data.bekerja,
                            data.kuliah,
                            data.wirausaha,
                            data.belum_bekerja
                        ],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.9)', // Green-500
                            'rgba(99, 102, 241, 0.9)', // Indigo-500
                            'rgba(251, 191, 36, 0.9)', // Amber-400
                            'rgba(107, 114, 128, 0.9)' // Gray-500
                        ],
                        borderColor: '#fff',
                        borderWidth: 3,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 25, font: { size: 14 } }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${new Intl.NumberFormat('id-ID').format(value)} Alumni (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error("Error initializing Status Chart:", e);
        }
    }

    /**
     * Inisialisasi Bar Chart Waktu Tunggu Kerja
     */
    function initWaktuTungguChart() {
        const waktuTungguCtx = document.getElementById('waktuTungguChart');
        if (!waktuTungguCtx) return;

        try {
            const chartDataAttr = waktuTungguCtx.getAttribute('data-chart-data');
            const data = JSON.parse(chartDataAttr);

             if (!data || Object.values(data).every(v => v === 0)) {
                return; // Tidak perlu inisialisasi jika data kosong
            }

            new Chart(waktuTungguCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['< 3 Bulan', '3-6 Bulan', '6-12 Bulan', '> 12 Bulan'],
                    datasets: [{
                        label: 'Jumlah Alumni',
                        data: [
                            data.tunggu_3,
                            data.tunggu_6,
                            data.tunggu_12,
                            data.tunggu_lebih
                        ],
                        backgroundColor: 'rgba(79, 70, 229, 0.85)', // Indigo-600
                        borderColor: 'rgba(55, 48, 163, 1)', // Indigo-800
                        borderWidth: 1,
                        borderRadius: 8,
                        hoverBackgroundColor: 'rgba(99, 102, 241, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                font: { size: 12 }
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Alumni',
                                font: { size: 14, weight: 'bold' }
                            },
                            grid: {
                                display: true,
                                color: 'rgba(200, 200, 200, 0.2)'
                            }
                        },
                        x: {
                            ticks: { font: { size: 12 } },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                             backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.dataset.label}: ${context.parsed.y} Alumni`;
                                }
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error("Error initializing Waktu Tunggu Chart:", e);
        }
    }
</script>
<script>
    // Ambil nilai dari PHP dan pastikan default ke 0 jika tidak ada
    const dataBekerja = {{ $statistik['bekerja'] ?? 0 }};
    const dataWirausaha = {{ $statistik['wirausaha'] ?? 0 }};
    const dataStudi = {{ $statistik['melanjutkan_studi'] ?? 0 }};
    const dataBelumBekerja = {{ $statistik['belum_bekerja'] ?? 0 }};

    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Belum Bekerja'],
            datasets: [{
                data: [dataBekerja, dataWirausaha, dataStudi, dataBelumBekerja],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.9)', // Green-500
                    'rgba(59, 130, 246, 0.9)', // Blue-500
                    'rgba(6, 182, 212, 0.9)',  // Cyan-500
                    'rgba(245, 158, 11, 0.9)'  // Amber-500
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        font: {
                            family: 'Inter, sans-serif'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    bodyFont: {
                        family: 'Inter, sans-serif'
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += new Intl.NumberFormat('id-ID').format(value) + ` (${percentage}%)`;
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection