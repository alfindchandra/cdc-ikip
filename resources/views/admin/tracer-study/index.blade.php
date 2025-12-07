@extends('layouts.app')

@section('title', 'Tracer Study')
@section('page-title', 'Tracer Study')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-8" x-data="{ openFilter: false }">

    {{-- Header & Aksi --}}
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center mb-3 sm:mb-0">
            <i class="fas fa-chart-line mr-3 text-indigo-600"></i><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Tracer Study</span>
        </h1>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('admin.tracer-study.laporan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-file-alt mr-2"></i>Laporan Analisis
            </a>
        </div>
    </header>

    {{-- Statistik Cards (Key Metrics) --}}
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                'total_alumni' => ['icon' => 'fas fa-graduation-cap', 'color' => 'indigo', 'label' => 'Total Alumni', 'unit' => '', 'value' => $statistik['total_alumni']],
                'total_responden' => ['icon' => 'fas fa-users', 'color' => 'green', 'label' => 'Responden', 'unit' => 'Jumlah', 'value' => $statistik['total_responden'], 'percent' => $statistik['total_alumni'] > 0 ? round(($statistik['total_responden'] / $statistik['total_alumni']) * 100, 1) : 0],
                'rata_waktu_tunggu' => ['icon' => 'fas fa-clock', 'color' => 'cyan', 'label' => 'Waktu Tunggu Rata-rata', 'unit' => 'Bulan', 'value' => $statistik['rata_waktu_tunggu'] ?? 0],
                'rata_kepuasan' => ['icon' => 'fas fa-star', 'color' => 'amber', 'label' => 'Kepuasan Rata-rata', 'unit' => '/ 5', 'value' => $statistik['rata_kepuasan'] ?? 0],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="bg-white overflow-hidden shadow-2xl rounded-2xl border-l-4 border-{{ $card['color'] }}-500 transition duration-300 transform hover:scale-[1.02]">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <p class="text-sm font-medium text-{{ $card['color'] }}-600 uppercase tracking-wider mb-1">{{ $card['label'] }}</p>
                            <div class="text-3xl font-bold text-gray-800 flex items-end">
                                {{ $card['value'] }}
                                @if(isset($card['percent']))
                                    <small class="text-base font-normal text-gray-500 ml-2">({{ $card['percent'] }}%)</small>
                                @else
                                    <span class="text-base font-normal text-gray-500 ml-2">{{ $card['unit'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-auto p-3 bg-{{ $card['color'] }}-100 rounded-full">
                            <i class="{{ $card['icon'] }} text-{{ $card['color'] }}-500 text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    {{-- Charts and Breakdown --}}
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

    {{-- Data Tracer Study Table --}}
    <section class="bg-white shadow-2xl rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-indigo-700">Data Tracer Study <i class="fas fa-database ml-2 text-indigo-400"></i></h2>
            <button @click="openFilter = !openFilter" class="lg:hidden text-indigo-600 hover:text-indigo-800 transition duration-150 p-2 rounded-lg bg-white shadow-sm">
                <i :class="openFilter ? 'fas fa-times' : 'fas fa-filter'"></i>
            </button>
        </div>
        
        <div class="p-6">
            {{-- Filter Form --}}
           {{-- Filter Form --}}
<form method="GET" action="{{ route('admin.tracer-study.index') }}" class="mb-6 space-y-4" x-show="openFilter || window.innerWidth >= 1024" x-collapse.duration.500ms>
    {{-- Grid diubah menjadi 7 kolom (2 search, 1 status, 1 tahun, 1 fakultas, 1 prodi, 1 aksi) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-7 gap-4"> 
        {{-- Pencarian --}}
        <div class="col-span-full md:col-span-2 lg:col-span-2">
            <label for="search" class="sr-only">Cari nama/NIM...</label>
            <div class="relative">
                <input type="text" name="search" id="search" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-150" placeholder="Cari nama/NIM..." value="{{ request('search') }}">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        {{-- Filter Status (tetap 1 kolom) --}}
        <div class="col-span-full sm:col-span-1 lg:col-span-1">
            <label for="status_pekerjaan" class="sr-only">Status</label>
            <select name="status_pekerjaan" id="status_pekerjaan" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 appearance-none bg-white">
                <option value="">Semua Status</option>
                <option value="bekerja" {{ request('status_pekerjaan') == 'bekerja' ? 'selected' : '' }}>Bekerja</option>
                <option value="wirausaha" {{ request('status_pekerjaan') == 'wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                <option value="melanjutkan_studi" {{ request('status_pekerjaan') == 'melanjutkan_studi' ? 'selected' : '' }}>Melanjutkan Studi</option>
                <option value="belum_bekerja" {{ request('status_pekerjaan') == 'belum_bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
            </select>
        </div>

        {{-- Filter Tahun Lulus (tetap 1 kolom) --}}
        <div class="col-span-full sm:col-span-1 lg:col-span-1">
            <label for="tahun_lulus" class="sr-only">Tahun Lulus</label>
            <select name="tahun_lulus" id="tahun_lulus" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 appearance-none bg-white">
                <option value="">Semua Tahun</option>
                @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                    <option value="{{ $year }}" {{ request('tahun_lulus') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </div>
        
        {{-- FILTER FAKULTAS (TAMBAHAN) --}}
        <div class="col-span-full sm:col-span-1 lg:col-span-1">
            <label for="fakultas_id" class="sr-only">Fakultas</label>
            <select name="fakultas_id" id="fakultas_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 appearance-none bg-white">
                <option value="">Semua Fakultas</option>
                {{-- Pastikan variabel $fakultas sudah di-compact dari controller --}}
                @foreach($fakultas as $f)
                    <option value="{{ $f->id }}" {{ request('fakultas_id') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                @endforeach
            </select>
        </div>

        {{-- FILTER PROGRAM STUDI (OPSIONAL) --}}
        {{-- Jika Anda ingin menambahkan filter Prodi, gunakan kode di bawah ini:
        <div class="col-span-full sm:col-span-1 lg:col-span-1">
            <label for="program_studi_id" class="sr-only">Program Studi</label>
            <select name="program_studi_id" id="program_studi_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 appearance-none bg-white">
                <option value="">Semua Prodi</option>
                @foreach($program_studi as $ps)
                    <option value="{{ $ps->id }}" {{ request('program_studi_id') == $ps->id ? 'selected' : '' }}>{{ $ps->nama }}</option>
                @endforeach
            </select>
        </div>
        --}}

        {{-- Tombol Filter dan Reset (Digabungkan ke 2 kolom terakhir) --}}
        <div class="col-span-full md:col-span-2 lg:col-span-2 flex space-x-4">
            <button type="submit" class="flex-1 flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-[1.03]">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="{{ route('admin.tracer-study.index') }}" class="flex-1 flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>
    </div>
</form>

            {{-- Data Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tl-lg">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">NIM</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fakultas/Prodi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Info Detail</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Isi</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tracerStudy as $item)
                            <tr class="hover:bg-indigo-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration + $tracerStudy->firstItem() - 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900 font-semibold">{{ $item->siswa->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">Lulus: {{ $item->siswa->tahun_lulus ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->siswa->nim ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900 font-medium">{{ Str::words($item->siswa->fakultas->nama ?? '-', 3, '...') }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::words($item->siswa->programStudi->nama ?? '-', 3, '...') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $statusClass = [
                                            'bekerja' => 'bg-green-100 text-green-800',
                                            'wirausaha' => 'bg-blue-100 text-blue-800',
                                            'melanjutkan_studi' => 'bg-cyan-100 text-cyan-800',
                                            'belum_bekerja' => 'bg-amber-100 text-amber-800',
                                        ][$item->status_pekerjaan] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $item->status_pekerjaan_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($item->status_pekerjaan == 'bekerja')
                                        <div class="text-gray-900 font-medium">{{ $item->nama_perusahaan ?? '-' }}</div>
                                        <div class="text-xs">{{ $item->posisi ?? '-' }}</div>
                                    @elseif($item->status_pekerjaan == 'wirausaha')
                                        <div class="text-gray-900 font-medium">{{ $item->nama_usaha ?? '-' }}</div>
                                        <div class="text-xs">{{ $item->bidang_usaha ?? '-' }}</div>
                                    @elseif($item->status_pekerjaan == 'melanjutkan_studi')
                                        <div class="text-gray-900 font-medium">{{ $item->nama_institusi ?? '-' }}</div>
                                        <div class="text-xs">{{ $item->jenjang_studi_label ?? '-' }}</div>
                                    @else
                                        <div class="text-sm text-gray-600 italic">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->tanggal_isi ? $item->tanggal_isi->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.tracer-study.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-indigo-100 transition duration-150 transform hover:scale-110" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 .875-.252 1.02-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533l.738-3.468c.194-.897-.105-1.319-.808-1.319-.545 0-.875.252-1.02.598l-.088.416c.2-.176.492-.246.686-.246.275 0 .375.193.304.533zm-1.29-3.588c0 .535-.448.972-1 .972s-1-.437-1-.972S6.138 2 6.683 2s1 .437 1 1z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 whitespace-nowrap text-center text-lg text-gray-500 italic">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada data Tracer Study yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-between items-center">
                {{ $tracerStudy->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Memastikan Alpine.js tersedia (jika belum ada di layouts.app) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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