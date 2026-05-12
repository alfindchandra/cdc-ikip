<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'is_verified',
        'attempts',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate OTP 6 digit
     */
    public static function generateOtp()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Cek apakah OTP masih berlaku
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Cek apakah OTP sudah terverifikasi
     */
    public function isValid(): bool
    {
        return !$this->is_verified && !$this->isExpired() && $this->attempts < 3;
    }

    /**
     * Verifikasi OTP
     */
    public function verify(): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return true;
    }

    /**
     * Increment attempt
     */
    public function incrementAttempt(): void
    {
        $this->increment('attempts');
    }
}
