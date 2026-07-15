<?php

namespace App\Http\Controllers;

use App\Models\EPortfolio;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EPortfolioController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $portfolio = EPortfolio::where('mahasiswa_id', $mahasiswa->id)->first();

        return view('mahasiswa.eportfolio.index', compact('mahasiswa', 'portfolio'));
    }

    public function store(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $data = $request->validate([
            'profil_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:2048'],
            'pengalaman_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:2048'],
            'prestasi_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:2048'],
            'sertifikat_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:2048'],
        ]);

        $portfolio = EPortfolio::firstOrNew(['mahasiswa_id' => $mahasiswa->id]);
        $portfolio->mahasiswa_id = $mahasiswa->id;

        foreach (['profil_file', 'pengalaman_file', 'prestasi_file', 'sertifikat_file'] as $field) {
            if ($request->hasFile($field)) {
                $column = str_replace('_file', '_path', $field);

                // Hapus file lama secara fisik dari storage jika ada
                if ($portfolio->{$column} && Storage::disk('public')->exists($portfolio->{$column})) {
                    Storage::disk('public')->delete($portfolio->{$column});
                }

                // Simpan file baru
                $path = $request->file($field)->store('eportfolio', 'public');
                $portfolio->{$column} = $path;
            }
        }

        $portfolio->save();

        return back()->with('success', 'E-Portfolio berhasil diperbarui.');
    }

    public function showForCompany(Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()?->role === 'perusahaan', 403);

        $portfolio = EPortfolio::where('mahasiswa_id', $mahasiswa->id)->first();

        return view('perusahaan.eportfolio.show', compact('mahasiswa', 'portfolio'));
    }
}