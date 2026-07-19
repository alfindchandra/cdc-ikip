@extends('layouts.app')

@section('title', 'Edit Pertanyaan Tracer Study')
@section('page-title', 'Edit Pertanyaan Tracer Study')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- Header --}}
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 flex items-center">
                <i class="fas fa-list-alt mr-3 text-indigo-600"></i>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                    Konfigurasi Pertanyaan Tracer Study
                </span>
            </h1>
            <p class="mt-1 text-sm text-gray-500">Aktifkan/nonaktifkan pertanyaan, ubah label dan urutan tampilan.</p>
        </div>
        <a href="{{ route('admin.tracer-study.index') }}"
           class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </header>

    {{-- Alert --}}
    @if(session('success'))
        <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-xl text-green-800">
            <i class="fas fa-check-circle mr-3 text-green-500 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-xl text-red-800">
            <i class="fas fa-exclamation-circle mr-3 text-red-500 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Info Panel --}}
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex items-start gap-3">
        <i class="fas fa-info-circle text-indigo-500 mt-0.5 text-lg flex-shrink-0"></i>
        <div class="text-sm text-indigo-800">
            <strong>Catatan:</strong> Perubahan label dan status aktif/nonaktif akan memengaruhi tampilan form tracer study.
            Kolom yang dinonaktifkan tidak akan ditampilkan. Perubahan ini bersifat konfigurasi tampilan — data yang sudah ada tidak akan terhapus.
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.tracer-study.pertanyaan.update') }}" id="formPertanyaan">
        @csrf
        @method('PUT')

        @php
            $sectionIcons = [
                'status_pekerjaan'  => ['icon' => 'fas fa-briefcase',       'color' => 'indigo'],
                'data_pekerjaan'    => ['icon' => 'fas fa-building',        'color' => 'blue'],
                'pencarian_kerja'   => ['icon' => 'fas fa-search',          'color' => 'cyan'],
                'data_wirausaha'    => ['icon' => 'fas fa-store',           'color' => 'emerald'],
                'data_studi'        => ['icon' => 'fas fa-graduation-cap',  'color' => 'violet'],
                'data_ppg'          => ['icon' => 'fas fa-chalkboard-teacher','color' => 'pink'],
                'kompetensi'        => ['icon' => 'fas fa-star',            'color' => 'amber'],
                'kepuasan_feedback' => ['icon' => 'fas fa-comment-alt',     'color' => 'orange'],
                'kontak'            => ['icon' => 'fas fa-address-card',    'color' => 'teal'],
            ];
        @endphp

        @foreach($questions as $section => $items)
            @php
                $label   = $sectionLabels[$section] ?? ucwords(str_replace('_', ' ', $section));
                $si      = $sectionIcons[$section]  ?? ['icon' => 'fas fa-question-circle', 'color' => 'gray'];
                $activeCount = $items->where('is_active', true)->count();
            @endphp

            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                {{-- Section Header --}}
                <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-{{ $si['color'] }}-50 to-white border-b border-{{ $si['color'] }}-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-{{ $si['color'] }}-100 rounded-xl flex items-center justify-center">
                            <i class="{{ $si['icon'] }} text-{{ $si['color'] }}-600"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-800">{{ $label }}</h2>
                            <p class="text-xs text-gray-500">{{ $activeCount }} dari {{ $items->count() }} pertanyaan aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Questions List --}}
                <div class="divide-y divide-gray-100">
                    @foreach($items as $q)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150
                                    {{ $q->is_active ? '' : 'opacity-60' }}" id="question-row-{{ $q->id }}">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-4">

                                {{-- Toggle Aktif --}}
                                <div class="flex-shrink-0 flex items-center gap-3 lg:w-16">
                                    <label class="relative inline-flex items-center cursor-pointer" title="{{ $q->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <input type="checkbox"
                                               name="questions[{{ $q->id }}][is_active]"
                                               value="1"
                                               {{ $q->is_active ? 'checked' : '' }}
                                               onchange="toggleRow({{ $q->id }}, this.checked)"
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 rounded-full peer
                                                    peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute
                                                    after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                                    after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>

                                {{-- Field Info & Label Edit --}}
                                <div class="flex-1 space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-600">
                                            {{ $q->field_name }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                     bg-{{ $si['color'] }}-100 text-{{ $si['color'] }}-700">
                                            {{ $q->type_label }}
                                        </span>
                                        @if($q->is_required)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                <i class="fas fa-asterisk mr-1 text-xs"></i>Wajib
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Label --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Label Pertanyaan</label>
                                        <input type="text"
                                               name="questions[{{ $q->id }}][label]"
                                               value="{{ old('questions.'.$q->id.'.label', $q->label) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 transition">
                                    </div>

                                    {{-- Helper Text --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Teks Bantuan (opsional)</label>
                                        <input type="text"
                                               name="questions[{{ $q->id }}][helper_text]"
                                               value="{{ old('questions.'.$q->id.'.helper_text', $q->helper_text) }}"
                                               placeholder="Contoh: Isi 0 jika sudah bekerja sebelum lulus"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 transition">
                                    </div>
                                </div>

                                {{-- Sort Order & Required --}}
                                <div class="flex-shrink-0 flex flex-row lg:flex-col items-center lg:items-end gap-4 lg:gap-2">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1 lg:text-right">Urutan</label>
                                        <input type="number"
                                               name="questions[{{ $q->id }}][sort_order]"
                                               value="{{ $q->sort_order }}"
                                               min="1" max="99"
                                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 transition">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <label class="text-xs font-semibold text-gray-500">Wajib</label>
                                        <input type="checkbox"
                                               name="questions[{{ $q->id }}][is_required]"
                                               value="1"
                                               {{ $q->is_required ? 'checked' : '' }}
                                               class="w-4 h-4 text-red-500 rounded border-gray-300 focus:ring-red-300">
                                    </div>
                                </div>
                            </div>

                            {{-- Preview pilihan (jika ada options) --}}
                            @if($q->options && count($q->options) > 0)
                                <div class="mt-3 pl-16">
                                    <p class="text-xs text-gray-400 mb-1"><i class="fas fa-list mr-1"></i>Pilihan tersedia ({{ count($q->options) }} item):</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($q->options as $val => $text)
                                            <span class="px-2 py-0.5 bg-gray-100 rounded text-xs text-gray-600">{{ $text }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Save Button --}}
        <div class="sticky bottom-4 flex justify-end gap-3 z-10">
            <a href="{{ route('admin.tracer-study.index') }}"
               class="px-6 py-3 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-xl shadow-lg hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-[1.02]">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleRow(id, isActive) {
        const row = document.getElementById('question-row-' + id);
        if (isActive) {
            row.classList.remove('opacity-60');
        } else {
            row.classList.add('opacity-60');
        }
    }
</script>
@endpush
@endsection
