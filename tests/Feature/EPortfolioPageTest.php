<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EPortfolioPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('role', ['admin', 'mahasiswa', 'perusahaan'])->default('mahasiswa');
            $table->timestamps();
        });

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('nim')->unique();
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        Schema::create('e_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa');
            $table->text('profil_kompetensi')->nullable();
            $table->text('pengalaman_kerja')->nullable();
            $table->text('prestasi')->nullable();
            $table->text('sertifikat')->nullable();
            $table->string('profil_path')->nullable();
            $table->string('pengalaman_path')->nullable();
            $table->string('prestasi_path')->nullable();
            $table->string('sertifikat_path')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('e_portfolios');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_mahasiswa_can_access_eportfolio_page(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '20250001',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->get(route('mahasiswa.eportfolio.index'));

        $response->assertStatus(200);
        $response->assertSee('E-Portfolio Mahasiswa');
        $response->assertSee('Profil Kompetensi');
    }

    public function test_perusahaan_can_access_mahasiswa_eportfolio_page(): void
    {
        $mahasiswaUser = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa = Mahasiswa::create([
            'user_id' => $mahasiswaUser->id,
            'nim' => '20250002',
            'status' => 'aktif',
        ]);

        $companyUser = User::factory()->create(['role' => 'perusahaan']);

        $response = $this->actingAs($companyUser)->get(route('perusahaan.mahasiswa.eportfolio.show', $mahasiswa));

        $response->assertStatus(200);
        $response->assertSee('E-Portfolio');
        $response->assertSee($mahasiswaUser->name);
    }
}
