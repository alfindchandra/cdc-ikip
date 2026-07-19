<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudyQuestion extends Model
{
    use HasFactory;

    protected $table = 'tracer_study_questions';

    protected $fillable = [
        'section',
        'field_name',
        'label',
        'type',
        'options',
        'is_required',
        'is_active',
        'sort_order',
        'helper_text',
    ];

    protected $casts = [
        'options'     => 'array',
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Label section yang lebih ramah pengguna
     */
    public function getSectionLabelAttribute(): string
    {
        return match ($this->section) {
            'status_pekerjaan'   => 'Status Pekerjaan',
            'data_pekerjaan'     => 'Data Pekerjaan',
            'data_wirausaha'     => 'Data Wirausaha',
            'data_studi'         => 'Data Melanjutkan Studi',
            'data_ppg'           => 'Pendidikan Profesi Guru (PPG)',
            'kompetensi'         => 'Kompetensi & Metode Pembelajaran',
            'pencarian_kerja'    => 'Pencarian Kerja',
            'kepuasan_feedback'  => 'Kepuasan & Saran',
            'kontak'             => 'Informasi Kontak',
            default              => ucwords(str_replace('_', ' ', $this->section)),
        };
    }

    /**
     * Label tipe input
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'text'     => 'Teks Singkat',
            'textarea' => 'Teks Panjang',
            'number'   => 'Angka',
            'email'    => 'Email',
            'url'      => 'URL/Link',
            'radio'    => 'Pilihan Tunggal (Radio)',
            'select'   => 'Dropdown',
            'checkbox' => 'Pilihan Ganda (Checkbox)',
            'date'     => 'Tanggal',
            default    => ucfirst($this->type),
        };
    }

    /**
     * Scope: hanya pertanyaan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: urut berdasarkan section dan sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('section')->orderBy('sort_order');
    }
}
