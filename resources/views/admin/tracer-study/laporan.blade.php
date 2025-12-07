@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="fas fa-chart-bar text-blue-600 mr-3"></i> Laporan & Analisis Tracer Study
        </h1>
        <div class="flex space-x-3 mt-4 md:mt-0">
            
            <a href="{{ route('admin.tracer-study.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden mb-8 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                <select name="tahun_lulus" id="tahun_lulus" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Tahun</option>
                    @for($year = date('Y'); $year >= date('Y') - 10; $year--)
                        <option value="{{ $year }}" {{ $tahunLulus == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="fakultas_id" class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                <select name="fakultas_id" id="fakultas_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultas as $fak)
                        <option value="{{ $fak->id }}" {{ $fakultasId == $fak->id ? 'selected' : '' }}>{{ $fak->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-filter mr-2"></i> Terapkan Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        
        @php
            // Utility function for card styling
            $statCard = fn($title, $value, $percentage, $colorClass) => [
                'title' => $title,
                'value' => $value,
                'percentage' => $percentage,
                'color' => $colorClass,
            ];

            $stats = [
                $statCard('Total Responden', $analisis['total_responden'], null, 'border-blue-500 text-blue-800'),
                $statCard('Bekerja', $analisis['bekerja'], $analisis['total_responden'] > 0 ? round(($analisis['bekerja'] / $analisis['total_responden']) * 100, 1) : 0, 'border-green-500 text-green-800'),
                $statCard('Wirausaha', $analisis['wirausaha'], $analisis['total_responden'] > 0 ? round(($analisis['wirausaha'] / $analisis['total_responden']) * 100, 1) : 0, 'border-indigo-500 text-indigo-800'),
                $statCard('Studi Lanjut', $analisis['melanjutkan_studi'], $analisis['total_responden'] > 0 ? round(($analisis['melanjutkan_studi'] / $analisis['total_responden']) * 100, 1) : 0, 'border-cyan-500 text-cyan-800'),
                $statCard('Belum Bekerja', $analisis['belum_bekerja'], $analisis['total_responden'] > 0 ? round(($analisis['belum_bekerja'] / $analisis['total_responden']) * 100, 1) : 0, 'border-yellow-500 text-yellow-800'),
                $statCard('Rata-rata Kepuasan', $analisis['rata_kepuasan'] ?? 0 . ' / 5', null, 'border-gray-500 text-gray-800'),
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 {{ $stat['color'] }} transition duration-300 hover:shadow-xl">
            <div class="text-xs font-semibold uppercase mb-1 {{ $stat['color'] }}">{{ $stat['title'] }}</div>
            <div class="text-2xl font-bold text-gray-900">
                {{ $stat['value'] }}
                @if($stat['percentage'] !== null)
                    <span class="text-sm font-medium text-gray-500 ml-1">({{ $stat['percentage'] }}%)</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Distribusi Status Pekerjaan</h3>
            </div>
            <div class="p-6">
                <canvas id="statusChart" class="h-64"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Relevansi Pekerjaan dengan Jurusan</h3>
            </div>
            <div class="p-6">
                <canvas id="relevansiChart" class="h-64"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Distribusi Jenis Perusahaan</h3>
            </div>
            <div class="p-6">
                <canvas id="jenisPerusahaanChart" class="h-64"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-700">Distribusi Range Penghasilan</h3>
            </div>
            <div class="p-6">
                <canvas id="penghasilanChart" class="h-64"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
        
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-green-600 p-4">
                <h3 class="text-xl font-semibold text-white">Alumni yang Bekerja</h3>
            </div>
            <div class="p-6">
                <dl class="space-y-3 text-sm">
                    @php
                        $bekerjaData = [
                            'Total Yang Bekerja' => $analisis['bekerja'] . ' orang',
                            'Persentase' => ($analisis['total_responden'] > 0 ? round(($analisis['bekerja'] / $analisis['total_responden']) * 100, 1) : 0) . '%',
                            'Rata-rata Waktu Tunggu' => ($analisis['rata_waktu_tunggu'] ?? 0) . ' bulan',
                            'Sangat Relevan dengan Jurusan' => $analisis['sangat_relevan'] . ' orang',
                            'Relevan dengan Jurusan' => $analisis['relevan'] . ' orang',
                            'Cukup Relevan' => $analisis['cukup_relevan'] . ' orang',
                            'Tidak Relevan' => $analisis['tidak_relevan'] . ' orang',
                        ];
                    @endphp

                    @foreach($bekerjaData as $label => $value)
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <dt class="text-gray-600">{{ $label }}</dt>
                            <dd class="font-bold text-gray-900">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="bg-blue-600 p-4">
                <h3 class="text-xl font-semibold text-white">Alumni Wirausaha & Studi Lanjut</h3>
            </div>
            <div class="p-6">
                
                <h4 class="text-lg font-bold text-indigo-700 mb-3 border-b pb-1">Wirausaha</h4>
                <dl class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <dt class="text-gray-600">Total Wirausaha</dt>
                        <dd class="font-bold text-gray-900">{{ $analisis['wirausaha'] }} orang</dd>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <dt class="text-gray-600">Persentase</dt>
                        <dd class="font-bold text-gray-900">{{ $analisis['total_responden'] > 0 ? round(($analisis['wirausaha'] / $analisis['total_responden']) * 100, 1) : 0 }}%</dd>
                    </div>
                </dl>

                <h4 class="text-lg font-bold text-cyan-700 mb-3 border-b pb-1">Melanjutkan Studi</h4>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <dt class="text-gray-600">Total Melanjutkan Studi</dt>
                        <dd class="font-bold text-gray-900">{{ $analisis['melanjutkan_studi'] }} orang</dd>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                        <dt class="text-gray-600">Persentase</dt>
                        <dd class="font-bold text-gray-900">{{ $analisis['total_responden'] > 0 ? round(($analisis['melanjutkan_studi'] / $analisis['total_responden']) * 100, 1) : 0 }}%</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="bg-indigo-600 p-4">
            <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-table mr-2"></i> Detail Data Alumni
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Lulus</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kepuasan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $index => $item)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->siswa->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->siswa->nim }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->siswa->programStudi->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->siswa->tahun_lulus ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusBadge = [
                                        'bekerja' => ['bg-green-100', 'text-green-800', 'Bekerja'],
                                        'wirausaha' => ['bg-blue-100', 'text-blue-800', 'Wirausaha'],
                                        'melanjutkan_studi' => ['bg-indigo-100', 'text-indigo-800', 'Studi Lanjut'],
                                    ][$item->status_pekerjaan] ?? ['bg-yellow-100', 'text-yellow-800', 'Belum Bekerja'];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                                    {{ $statusBadge[2] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 max-w-xs truncate">
                                @if($item->status_pekerjaan == 'bekerja')
                                    <span class="font-semibold">{{ $item->nama_perusahaan }}</span>, {{ $item->posisi }}
                                @elseif($item->status_pekerjaan == 'wirausaha')
                                    {{ $item->nama_usaha }}
                                @elseif($item->status_pekerjaan == 'melanjutkan_studi')
                                    {{ $item->nama_institusi }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-yellow-600">
                                @if($item->kepuasan_pendidikan)
                                    {{ $item->kepuasan_pendidikan }}/5
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data responden yang sesuai dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pastikan Anda juga memasukkan library Alpine.js di layout utama Anda jika Anda ingin menggunakannya untuk fungsionalitas di masa depan.

    // -------------------
    // Chart.js Configuration
    // -------------------
    
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Belum Bekerja'],
            datasets: [{
                data: [
                    {{ $analisis['bekerja'] }},
                    {{ $analisis['wirausaha'] }},
                    {{ $analisis['melanjutkan_studi'] }},
                    {{ $analisis['belum_bekerja'] }}
                ],
                backgroundColor: [
                    '#10B981', // green-500
                    '#3B82F6', // blue-500
                    '#6366F1', // indigo-500
                    '#F59E0B'  // yellow-500
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                }
            }
        }
    });

    // Relevansi Chart
    const relevansiCtx = document.getElementById('relevansiChart').getContext('2d');
    new Chart(relevansiCtx, {
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
                    '#10B981', 
                    '#3B82F6', 
                    '#67E8F9', // cyan-300
                    '#EF4444' // red-500
                ],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Jenis Perusahaan Chart
    @php
    $jenisPerusahaan = $data->where('status_pekerjaan', 'bekerja')->groupBy('jenis_perusahaan')->map->count();
    @endphp
    const jenisCtx = document.getElementById('jenisPerusahaanChart').getContext('2d');
    new Chart(jenisCtx, {
        type: 'doughnut',
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
                    '#F87171', // red-400
                    '#34D399', // green-400
                    '#60A5FA', // blue-400
                    '#A78BFA', // violet-400
                    '#FBBF24'  // amber-400
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Penghasilan Chart
    @php
    // Since Chart.js needs counts of specific categories, and your PHP provides a grouped collection,
    // we need to make sure the counts are extracted correctly for the fixed labels.
    $penghasilanCounts = [
        $data->where('penghasilan', '< 3 juta')->count(),
        $data->where('penghasilan', '3-5 juta')->count(),
        $data->where('penghasilan', '5-7 juta')->count(),
        $data->where('penghasilan', '7-10 juta')->count(),
        $data->where('penghasilan', '> 10 juta')->count(),
    ];
    @endphp
    const penghasilanCtx = document.getElementById('penghasilanChart').getContext('2d');
    new Chart(penghasilanCtx, {
        type: 'bar',
        data: {
            labels: ['< 3 jt', '3-5 jt', '5-7 jt', '7-10 jt', '> 10 jt'],
            datasets: [{
                label: 'Jumlah Alumni',
                data: @json($penghasilanCounts),
                backgroundColor: '#06B6D4' // cyan-600
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
@endsection