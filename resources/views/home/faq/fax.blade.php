{{-- Menggunakan template utama --}}
@extends('layouts.index')

@section('title', 'Tentang Kami, Hubungi Kami & FAQ - CDC IKIP')

@section('home')
{{-- Hero Banner & Deskripsi Terpadu Platform CDC --}}
<div class="position-relative overflow-hidden text-center bg-light py-5" style="margin-top: 75px; background: linear-gradient(135deg, #f4f7fb 0%, #ffffff 100%); border-bottom: 1px solid #e3eaf2;">
    <div class="col-md-8 p-lg-5 mx-auto my-4">
        <span class="badge bg-primary bg-opacity-10 text-3xl font-bold px-3 py-2 rounded-pill fw-semibold mb-3">Pusat Pengembangan Karier</span>
        <h1 class="display-5 fw-bold text-dark mb-3">Career Development Center (CDC) IKIP</h1>
        <h4 class="fw-normal text-muted mb-4">Sistem Informasi Penyerapan Lulusan, Magang, dan Kemitraan Industri</h4>
        <p class="lead text-secondary mx-auto" style="max-width: 800px; font-size: 1.05rem; line-height: 1.7;">
            <strong>CDC IKIP</strong> merupakan platform karier digital terintegrasi yang dirancang khusus untuk memfasilitasi mahasiswa aktif dan alumni dalam meraih peluang masa depan terbaik. Sistem ini berfungsi sebagai jembatan interaktif bagi <strong>Perusahaan Mitra</strong> untuk mempublikasikan lowongan kerja, mengelola berkas administrasi Kerja Sama Industri (MoU/MoA), serta memantau validasi berkas Magang mahasiswa. Didukung dengan fitur otentikasi keamanan OTP, modul Pelatihan Kompetensi, hingga pelacakan data alumni (Tracer Study), kami berkomitmen menyelaraskan dunia akademik dengan kebutuhan riil Dunia Usaha Dunia Industri (DUDI).
        </p>
    </div>
</div>

{{-- Main Content Section --}}
<div class="container py-5">
    <div class="row g-5">
        
        {{-- Kolom Kiri: Informasi Kontak & Layanan Fax (Sticky) --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 bg-white sticky-top" style="top: 100px; border-radius: 16px;">
                <h4 class="fw-bold text-dark mb-2">Hubungi Kami</h4>
                <p class="text-muted small mb-4">Layanan administrasi korporat, korespondensi kerja sama, dan verifikasi akun mitra resmi.</p>
                
                {{-- Fax --}}
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-printer-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Nomor Fax Resmi (Kemitraan)</h6>
                        <p class="text-secondary mb-1 fw-semibold">(021) 1234-5678</p>
                        <small class="text-muted d-block" style="font-size: 0.8rem; line-height: 1.4;">
                            *Gunakan saluran faksimili ini khusus untuk pengiriman draf dokumen fisik Nota Kesepahaman (MoU) industri.
                        </small>
                    </div>
                </div>

                {{-- Telepon --}}
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-telephone-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Layanan Telepon</h6>
                        <p class="text-secondary mb-0">(021) 8765-4321</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-info bg-opacity-10 text-info rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-envelope-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Email Korespondensi</h6>
                        <p class="text-secondary mb-0 text-break">cdc@ikip-pqr.ac.id</p>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="d-flex align-items-start">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; min-width: 50px;">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Sekretariat Korporat</h6>
                        <p class="text-secondary mb-0 small" style="line-height: 1.5;">
                            Gedung Rektorat Utama, Sayap Barat Lantai 2, Kampus Pusat IKIP.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: FAQ Accordion Section --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 16px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="p-2 bg-primary rounded text-white me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-question-circle-fill fs-5"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-0">Pertanyaan yang Sering Diajukan (FAQ)</h4>
                </div>
                
                <div class="accordion accordion-flush" id="faqAccordion">
                    @if(isset($faqs) && count($faqs) > 0)
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item border-0 border-bottom py-3">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed fw-bold text-dark bg-transparent px-0 fs-6 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body px-0 text-secondary" style="line-height: 1.6; font-size: 0.95rem;">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Fallback jika data FAQ statis langsung ditulis di blade --}}
                        <div class="accordion-item border-0 border-bottom py-3">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed fw-bold text-dark bg-transparent px-0 fs-6 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    Bagaimana cara perusahaan mendaftar dan mengajukan kerja sama?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-secondary" style="line-height: 1.6;">
                                    Perusahaan dapat memilih menu registrasi khusus Perusahaan Mitra pada halaman utama. Setelah mengisi identitas organisasi, Admin CDC akan memverifikasi profil Anda dalam waktu 1-3 hari kerja sebelum fitur unggah lowongan dan draf MoU aktif di dashboard Anda.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 border-bottom py-3">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed fw-bold text-dark bg-transparent px-0 fs-6 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    Apakah mahasiswa diwajibkan melakukan validasi berkas jurnal Magang/PKL?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-secondary" style="line-height: 1.6;">
                                    Ya, melalui sistem CDC ini mahasiswa dapat mengisi entri harian jurnal kegiatan magang, mengunggah laporan akhir, serta mengajukan persetujuan validasi berkas secara langsung baik kepada pembimbing internal kampus maupun perwakilan industri terkait.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 border-bottom py-3">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed fw-bold text-dark bg-transparent px-0 fs-6 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    Apa fungsi instrumen Tracer Study pada platform ini?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body px-0 text-secondary" style="line-height: 1.6;">
                                    Tracer Study merupakan kuesioner pelacakan karier berkala bagi alumni. Data masukan ini sangat berharga bagi manajemen kampus untuk memetakan relevansi kurikulum, mempersiapkan sertifikasi pelatihan berkala, serta menyusun laporan performa lulusan demi keperluan akreditasi institusi.
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection