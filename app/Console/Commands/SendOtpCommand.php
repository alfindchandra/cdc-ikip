<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Otp;
use App\Notifications\SendOtpNotification;
use Illuminate\Console\Command;

class SendOtpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:send-otp {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim OTP ke email pengguna untuk verifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        // Generate OTP
        $otpCode = Otp::generateOtp();

        // Hapus OTP lama yang belum terverifikasi
        $user->otps()->where('is_verified', false)->delete();

        // Buat OTP baru
        $otp = $user->otps()->create([
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Kirim notifikasi
        try {
            $user->notify(new SendOtpNotification($otpCode));
            $this->info("OTP berhasil dikirim ke {$email}");
            $this->info("OTP: {$otpCode}");
            $this->info("Berlaku hingga: {$otp->expires_at->format('d-m-Y H:i:s')}");
            return 0;
        } catch (\Exception $e) {
            $this->error("Gagal mengirim OTP: {$e->getMessage()}");
            return 1;
        }
    }
}
