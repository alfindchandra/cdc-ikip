<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model

{
    protected $table = 'notifikasi';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'judul', 'pesan', 'tipe', 'kategori', 'link', 'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}