@extends('layouts.app')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Sekolah -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Sekolah</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" value="{{ \App\Models\Pengaturan::get('nama_sekolah') }}" class="form-input">
                </div>

                <div>
                    <label class="form-label">Alamat Sekolah</label>
                    <textarea name="alamat_sekolah" rows="3" class="form-textarea">{{ \App\Models\Pengaturan::get('alamat_sekolah') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Email Sekolah</label>
                        <input type="email" name="email_sekolah" value="{{ \App\Models\Pengaturan::get('email_sekolah') }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="telp_sekolah" value="{{ \App\Models\Pengaturan::get('telp_sekolah') }}" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Logo Sekolah</label>
                    @if(\App\Models\Pengaturan::get('logo_sekolah'))
                    <div class="mb-2">
                        <img src="{{ Storage::url(\App\Models\Pengaturan::get('logo_sekolah')) }}" alt="Logo" class="w-20 h-20 rounded-lg object-cover">
                    </div>
                    @endif
                    <input type="file" name="logo_sekolah" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                </div>
            </div>
        </div>

        <!-- Pengaturan Akademik -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Pengaturan Akademik</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <label class="form-label">Tahun Ajaran Aktif</label>
                    <input type="text" name="tahun_ajaran" value="{{ \App\Models\Pengaturan::get('tahun_ajaran') }}" class="form-input" placeholder="2024/2025">
                </div>

                <div>
                    <label class="form-label">Batas Minimal Bulan PKL</label>
                    <input type="number" name="batas_pkl" value="{{ \App\Models\Pengaturan::get('batas_pkl') }}" class="form-input" min="1" max="12">
                    <p class="text-xs text-gray-500 mt-1">Durasi minimal PKL dalam bulan</p>
                </div>

                <div>
                    <label class="form-label">Maksimal Ukuran Upload (KB)</label>
                    <input type="number" name="max_upload_size" value="{{ \App\Models\Pengaturan::get('max_upload_size') }}" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file yang dapat diupload</p>
                </div>
            </div>
        </div>

        <!-- Pengaturan Notifikasi -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Pengaturan Notifikasi</h3>
            </div>
            <div class="card-body">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="auto_notif" value="true" 
                           {{ \App\Models\Pengaturan::get('auto_notif') == 'true' ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    <div>
                        <span class="font-medium text-gray-900">Aktifkan Notifikasi Otomatis</span>
                        <p class="text-sm text-gray-500">Sistem akan mengirim notifikasi otomatis untuk event penting</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
    </div>
@endsection