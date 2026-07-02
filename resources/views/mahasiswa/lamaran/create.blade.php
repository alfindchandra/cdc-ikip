@extends('layouts.app')

@section('title', 'Lamar Pekerjaan')
@section('page-title', 'Lamar Pekerjaan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="space-y-6">
        
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.lowongan.show', $lowongan) }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">Kembali ke Detail Lowongan</span>
        </a>

        <!-- Job Info Card -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl shadow-xl p-8 text-white">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                @if($lowongan->perusahaan->logo)
                    <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" 
                         alt="{{ $lowongan->perusahaan->nama_perusahaan }}" 
                         class="w-20 h-20 rounded-2xl object-cover ring-4 ring-white/30">
                @else
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center ring-4 ring-white/30">
                        <span class="text-white font-bold text-3xl">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                    </div>
                @endif
                
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">{{ $lowongan->judul }}</h1>
                    <p class="text-lg text-white/90 mb-3">{{ $lowongan->posisi }}</p>
                    <div class="flex flex-wrap gap-3">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                            {{ $lowongan->perusahaan->nama_perusahaan }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                            📍 {{ $lowongan->lokasi }}
                        </span>
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-sm font-medium">
                            {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Form -->
        <form action="{{ route('mahasiswa.lamaran.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="bg-white rounded-3xl shadow-lg border border-gray-100"
              id="applicationForm">
            @csrf
            <input type="hidden" name="lowongan_id" value="{{ $lowongan->id }}">

            <!-- Form Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Formulir Lamaran</h2>
                        <p class="text-sm text-gray-600">Lengkapi dokumen yang diperlukan untuk melamar</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="px-8 py-8 space-y-8">
                
                <!-- CV Upload (Required) -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Curriculum Vitae (CV) <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" 
                               name="cv" 
                               id="cv"
                               accept=".pdf" 
                               class="hidden"
                               required
                               onchange="handleFileSelect(this, 'cvPreview')">
                        
                        <label for="cv" 
                               class="flex items-center justify-center w-full px-6 py-8 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition group">
                            <div class="text-center" id="cvPreview">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-blue-600 mb-1">
                                    Klik untuk upload CV Anda
                                </p>
                                <p class="text-xs text-gray-500">
                                    Format: PDF • Maksimal 5MB
                                </p>
                            </div>
                        </label>
                    </div>
                    
                    @error('cv')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="border-t border-gray-200"></div>

                <!-- Surat Lamaran Upload (Optional) -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Surat Lamaran <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" 
                               name="surat_lamaran" 
                               id="surat_lamaran"
                               accept=".pdf" 
                               class="hidden"
                               onchange="handleFileSelect(this, 'suratPreview')">
                        
                        <label for="surat_lamaran" 
                               class="flex items-center justify-center w-full px-6 py-8 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition group">
                            <div class="text-center" id="suratPreview">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-purple-600 mb-1">
                                    Klik untuk upload Surat Lamaran
                                </p>
                                <p class="text-xs text-gray-500">
                                    Format: PDF • Maksimal 5MB
                                </p>
                            </div>
                        </label>
                    </div>
                    
                    @error('surat_lamaran')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="border-t border-gray-200"></div>

                <!-- Portofolio Upload (Optional) -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Portofolio <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    
                    <div class="relative">
                        <input type="file" 
                               name="portofolio" 
                               id="portofolio"
                               accept=".pdf" 
                               class="hidden"
                               onchange="handleFileSelect(this, 'portofolioPreview')">
                        
                        <label for="portofolio" 
                               class="flex items-center justify-center w-full px-6 py-8 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-green-500 hover:bg-green-50 transition group">
                            <div class="text-center" id="portofolioPreview">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400 group-hover:text-green-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-700 group-hover:text-green-600 mb-1">
                                    Klik untuk upload Portofolio
                                </p>
                                <p class="text-xs text-gray-500">
                                    Format: PDF • Maksimal 10MB
                                </p>
                            </div>
                        </label>
                    </div>
                    
                    @error('portofolio')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="border-t border-gray-200"></div>

                <!-- Additional Notes -->
                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Catatan Tambahan <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    
                    <textarea name="catatan" 
                              id="catatan"
                              rows="5" 
                              class="w-full px-5 py-4 border-2 border-gray-300 rounded-2xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition resize-none"
                              placeholder="Ceritakan mengapa Anda tertarik dengan posisi ini dan apa yang membuat Anda kandidat yang tepat... (maksimal 1000 karakter)"
                              maxlength="1000"></textarea>
                    
                    <div class="flex justify-between items-center text-xs">
                        <p class="text-gray-500">Jelaskan motivasi dan keunggulan Anda</p>
                        <p class="text-gray-400">
                            <span id="charCount">0</span>/1000 karakter
                        </p>
                    </div>
                    
                    @error('catatan')
                        <p class="text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-2">Tips Melamar:</h4>
                            <ul class="space-y-1 text-sm text-gray-700">
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 mt-0.5">✓</span>
                                    <span>Pastikan CV Anda terbaru dan relevan dengan posisi yang dilamar</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 mt-0.5">✓</span>
                                    <span>Semua dokumen harus dalam format PDF untuk kemudahan akses</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 mt-0.5">✓</span>
                                    <span>Tulis catatan yang jujur dan menunjukkan antusiasme Anda</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-blue-600 mt-0.5">✓</span>
                                    <span>Periksa kembali semua dokumen sebelum mengirim lamaran</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 rounded-b-3xl">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <p class="text-sm text-gray-600 text-center sm:text-left">
                        Dengan mengirim lamaran, Anda menyetujui data Anda diproses oleh perusahaan
                    </p>
                    <div class="flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('mahasiswa.lowongan.show', $lowongan) }}" 
                           class="flex-1 sm:flex-none px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition text-center">
                            Batal
                        </a>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl transition shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Lamaran
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for File Preview & Character Count -->
<script>
function handleFileSelect(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Convert to MB
        const fileName = file.name;
        
        preview.innerHTML = `
            <div class="flex items-center justify-center gap-3">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-left">
                    <p class="text-sm font-medium text-gray-900">${fileName}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB • Klik untuk ubah file</p>
                </div>
            </div>
        `;
    }
}

// Character counter for notes
const catatanTextarea = document.getElementById('catatan');
const charCount = document.getElementById('charCount');

if (catatanTextarea && charCount) {
    catatanTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
}

// Form validation before submit
document.getElementById('applicationForm').addEventListener('submit', function(e) {
    const cvInput = document.getElementById('cv');
    
    if (!cvInput.files || cvInput.files.length === 0) {
        e.preventDefault();
        alert('CV wajib diupload!');
        cvInput.focus();
        return false;
    }
});
</script>
@endsection