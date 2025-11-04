<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalPkl extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     * Dibuat sesuai dengan nama tabel di migrasi: 'jurnal_pkl'.
     * @var string
     */
    protected $table = 'jurnal_pkl';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     * Sesuai dengan kolom di migrasi, kecuali ID dan timestamps.
     * @var array
     */
    protected $fillable = [
        'pkl_id',
        'tanggal',
        'kegiatan',
        'foto',
        'status_validasi',
        'catatan_pembimbing',
    ];

    /**
     * Konversi tipe data untuk kolom tertentu.
     * Kolom 'tanggal' akan dikonversi menjadi objek Carbon.
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date',
        // 'status_validasi' bisa di-cast jika diperlukan, tapi string sudah cukup.
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi many-to-one ke Model Pkl.
     * Setiap jurnal PKL dimiliki oleh satu entri PKL.
     */
    public function pkl()
    {
        // Kunci asingnya adalah 'pkl_id', merujuk ke id di tabel 'pkl'
        return $this->belongsTo(Pkl::class, 'pkl_id');
    }
}
