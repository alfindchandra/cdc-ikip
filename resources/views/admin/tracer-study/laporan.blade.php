@extends('layouts.app')

@section('title', 'Laporan Tracer Study')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                        <i class="fas fa-chart-bar text-indigo-600 mr-3"></i>
                        Statistik & Laporan Tracer Study
                    </h1>
                    <p class="text-gray-600">IKIP PGRI Bojonegoro - Tahun {{ $tahunLulus ?? date('Y') }}</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <a href="{{ route('admin.tracer-study.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <form method="GET" action="{{ route('admin.tracer-study.laporan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lulus</label>
                    <select name="tahun_lulus" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Tahun</option>
                        @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                            <option value="{{ $year }}" {{ $tahunLulus == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fakultas</label>
                    <select name="fakultas_id" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Fakultas</option>
                        @foreach($fakultas as $fak)
                            <option value="{{ $fak->id }}" {{ $fakultasId == $fak->id ? 'selected' : '' }}>{{ $fak->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition">
                        <i class="fas fa-filter mr-2"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $summaryCards = [
                    [
                        'title' => 'Total Responden',
                        'value' => $analisis['total_responden'],
                        'icon' => 'fas fa-users',
                        'color' => 'blue',
                        'bg' => 'bg-blue-500'
                    ],
                    [
                        'title' => 'Bekerja',
                        'value' => $analisis['bekerja'],
                        'percentage' => $analisis['total_responden'] > 0 ? round(($analisis['bekerja'] / $analisis['total_responden']) * 100, 1) : 0,
                        'icon' => 'fas fa-briefcase',
                        'color' => 'green',
                        'bg' => 'bg-green-500'
                    ],
                    [
                        'title' => 'Waktu Tunggu Rata-rata',
                        'value' => ($analisis['rata_waktu_tunggu'] ?? 0),
                        'unit' => 'Bulan',
                        'icon' => 'fas fa-clock',
                        'color' => 'amber',
                        'bg' => 'bg-amber-500'
                    ],
                    [
                        'title' => 'Kepuasan Rata-rata',
                        'value' => ($analisis['rata_kepuasan'] ?? 0),
                        'unit' => '/ 5',
                        'icon' => 'fas fa-star',
                        'color' => 'yellow',
                        'bg' => 'bg-yellow-500'
                    ],
                ];
            @endphp

            @foreach($summaryCards as $card)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">{{ $card['title'] }}</p>
                                <p class="mt-2 text-4xl font-extrabold text-gray-900">
                                    {{ $card['value'] }}
                                    @if(isset($card['unit']))
                                        <span class="text-lg font-normal text-gray-500 ml-1">{{ $card['unit'] }}</span>
                                    @endif
                                </p>
                                @if(isset($card['percentage']))
                                    <p class="mt-1 text-sm text-{{ $card['color'] }}-600 font-semibold">{{ $card['percentage'] }}% dari total</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 {{ $card['bg'] }} rounded-full flex items-center justify-center">
                                    <i class="{{ $card['icon'] }} text-white text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Main Charts Row 1 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            {{-- Status Pekerjaan Chart --}}
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chart-pie text-indigo-600 mr-3"></i>
                    Distribusi Status Alumni
                </h3>
                <div class="h-80">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            {{-- Relevansi Pekerjaan Chart --}}
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                    Relevansi Pekerjaan dengan Jurusan
                </h3>
                <div class="h-80">
                    <canvas id="relevansiChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Main Charts Row 2 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            
            {{-- Jenis Perusahaan Chart --}}
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-building text-purple-600 mr-3"></i>
                    Jenis Perusahaan/Instansi
                </h3>
                <div class="h-80">
                    <canvas id="jenisPerusahaanChart"></canvas>
                </div>
            </div>

            {{-- Waktu Tunggu Kerja Chart --}}
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-clock text-amber-600 mr-3"></i>
                    Distribusi Waktu Tunggu Kerja
                </h3>
                <div class="h-80">
                    <canvas id="waktuTungguChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Tingkat Kepuasan --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-star text-yellow-500 mr-3"></i>
                Tingkat Kepuasan Alumni terhadap Pendidikan
            </h3>
            <div class="h-80">
                <canvas id="kepuasanChart"></canvas>
            </div>
        </div>

        {{-- Detail Statistics by Faculty --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-university text-blue-600 mr-3"></i>
                Statistik per Fakultas/Program Studi
            </h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fakultas/Prodi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Alumni</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Responden</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bekerja</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Wirausaha</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Studi Lanjut</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Response Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $fakultasStats = [];
                            foreach($fakultas as $fak) {
                                $alumniCount = \App\Models\Mahasiswa::where('fakultas_id', $fak->id)
                                    ->where('status', 'lulus')
                                    ->when($tahunLulus, function($q) use ($tahunLulus) {
                                        return $q->where('tahun_lulus', $tahunLulus);
                                    })
                                    ->count();
                                
                                $respondenData = $data->filter(function($item) use ($fak) {
                                    return $item->mahasiswa->fakultas_id == $fak->id;
                                });
                                
                                $respondenCount = $respondenData->count();
                                $bekerjaCount = $respondenData->where('status_pekerjaan', 'bekerja')->count();
                                $wirausahaCount = $respondenData->where('status_pekerjaan', 'wirausaha')->count();
                                $studiCount = $respondenData->where('status_pekerjaan', 'melanjutkan_studi')->count();
                                $responseRate = $alumniCount > 0 ? round(($respondenCount / $alumniCount) * 100, 1) : 0;
                                
                                if($respondenCount > 0 || $alumniCount > 0) {
                                    $fakultasStats[] = [
                                        'nama' => $fak->nama,
                                        'alumni' => $alumniCount,
                                        'responden' => $respondenCount,
                                        'bekerja' => $bekerjaCount,
                                        'wirausaha' => $wirausahaCount,
                                        'studi' => $studiCount,
                                        'rate' => $responseRate
                                    ];
                                }
                            }
                        @endphp

                        @forelse($fakultasStats as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $stat['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900 font-semibold">{{ $stat['alumni'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-900 font-semibold">{{ $stat['responden'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $stat['bekerja'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $stat['wirausaha'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $stat['studi'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $stat['rate'] }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $stat['rate'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Tidak ada data statistik</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detailed Alumni Table --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-table mr-3"></i> Detail Data Alumni
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Lulus</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kepuasan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($data as $index => $item)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-indigo-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->mahasiswa->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->mahasiswa->nim }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->mahasiswa->programStudi->nama ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">{{ $item->mahasiswa->tahun_lulus ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusBadge = [
                                            'bekerja' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Bekerja'],
                                            'wirausaha' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Wirausaha'],
                                            'melanjutkan_studi' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'label' => 'Studi Lanjut'],
                                            'belum_bekerja' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Belum Bekerja'],
                                        ][$item->status_pekerjaan] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Lainnya'];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($item->status_pekerjaan == 'bekerja')
                                        <span class="font-semibold">{{ $item->nama_perusahaan ?? '-' }}</span>
                                        @if($item->posisi), {{ $item->posisi }}@endif
                                    @elseif($item->status_pekerjaan == 'wirausaha')
                                        {{ $item->nama_usaha ?? '-' }}
                                    @elseif($item->status_pekerjaan == 'melanjutkan_studi')
                                        {{ $item->nama_institusi ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($item->kepuasan_pendidikan)
                                        <div class="flex items-center justify-center">
                                            <span class="text-lg font-bold text-yellow-600">{{ $item->kepuasan_pendidikan }}</span>
                                            <span class="text-sm text-gray-500 ml-1">/5</span>
                                            <div class="ml-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-xs {{ $i <= $item->kepuasan_pendidikan ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Tidak ada data responden yang sesuai dengan filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Configuration
    const chartColors = {
        blue: 'rgba(59, 130, 246, 0.8)',
        green: 'rgba(16, 185, 129, 0.8)',
        purple: 'rgba(139, 92, 246, 0.8)',
        yellow: 'rgba(245, 158, 11, 0.8)',
        red: 'rgba(239, 68, 68, 0.8)',
        indigo: 'rgba(99, 102, 241, 0.8)',
        pink: 'rgba(236, 72, 153, 0.8)',
        cyan: 'rgba(6, 182, 212, 0.8)',
    };

    // 1. Status Pekerjaan Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Belum Bekerja', 'Belum Memungkinkan'],
            datasets: [{
                data: [
                    {{ $analisis['bekerja'] }},
                    {{ $analisis['wirausaha'] }},
                    {{ $analisis['melanjutkan_studi'] }},
                    {{ $analisis['belum_bekerja'] }},
                    {{ $analisis['belum_memungkinkan_bekerja'] ?? 0 }}
                ],
                backgroundColor: [
                    chartColors.green,
                    chartColors.blue,
                    chartColors.purple,
                    chartColors.yellow,
                    'rgba(156, 163, 175, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 2. Relevansi Chart
    new Chart(document.getElementById('relevansiChart'), {
        type: 'bar',
        data: {
            labels: ['Sangat Relevan', 'Relevan', 'Cukup Relevan', 'Tidak Relevan'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: [
                    {{ $analisis['sangat_relevan'] }},
                    {{ $analisis['relevan'] }},
                    {{ $analisis['cukup_relevan'] }},
                    {{ $analisis['tidak_relevan'] }}
                ],
                backgroundColor: [
                    chartColors.green,
                    chartColors.blue,
                    chartColors.yellow,
                    chartColors.red
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. Jenis Perusahaan Chart
    @php
        $jenisPerusahaan = $data->where('status_pekerjaan', 'bekerja')->groupBy('jenis_perusahaan')->map->count();
    @endphp
    new Chart(document.getElementById('jenisPerusahaanChart'), {
        type: 'pie',
        data: {
            labels: ['Pemerintah', 'Swasta', 'BUMN', 'Startup', 'Lainnya'],
            datasets: [{
                data: [
                    {{ $jenisPerusahaan->get('pemerintah', 0) }},
                    {{ $jenisPerusahaan->get('swasta', 0) }},
                    {{ $jenisPerusahaan->get('bumn', 0) }},
                    {{ $jenisPerusahaan->get('startup', 0) }},
                    {{ $jenisPerusahaan->get('lainnya', 0) }}
                ],
                backgroundColor: [
                    chartColors.red,
                    chartColors.green,
                    chartColors.blue,
                    chartColors.purple,
                    chartColors.yellow
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 15,
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // 4. Waktu Tunggu Chart
    @php
        $waktuTungguData = $data->where('waktu_tunggu_kerja', '>', 0)->groupBy(function($item) {
            $bulan = $item->waktu_tunggu_kerja;
            if($bulan <= 3) return '0-3 bulan';
            if($bulan <= 6) return '4-6 bulan';
            if($bulan <= 12) return '7-12 bulan';
            return '> 12 bulan';
        })->map->count();
    @endphp
    new Chart(document.getElementById('waktuTungguChart'), {
        type: 'bar',
        data: {
            labels: ['0-3 bulan', '4-6 bulan', '7-12 bulan', '> 12 bulan'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: [
                    {{ $waktuTungguData->get('0-3 bulan', 0) }},
                    {{ $waktuTungguData->get('4-6 bulan', 0) }},
                    {{ $waktuTungguData->get('7-12 bulan', 0) }},
                    {{ $waktuTungguData->get('> 12 bulan', 0) }}
                ],
                backgroundColor: chartColors.indigo,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 5. Kepuasan Chart
    @php
        $kepuasanData = $data->where('kepuasan_pendidikan', '>', 0)->groupBy('kepuasan_pendidikan')->map->count();
    @endphp
    new Chart(document.getElementById('kepuasanChart'), {
        type: 'bar',
        data: {
            labels: ['1 (Sangat Tidak Puas)', '2 (Tidak Puas)', '3 (Cukup)', '4 (Puas)', '5 (Sangat Puas)'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: [
                    {{ $kepuasanData->get(1, 0) }},
                    {{ $kepuasanData->get(2, 0) }},
                    {{ $kepuasanData->get(3, 0) }},
                    {{ $kepuasanData->get(4, 0) }},
                    {{ $kepuasanData->get(5, 0) }}
                ],
                backgroundColor: [
                    chartColors.red,
                    'rgba(251, 146, 60, 0.8)',
                    chartColors.yellow,
                    'rgba(132, 204, 22, 0.8)',
                    chartColors.green
                ],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
@endsection