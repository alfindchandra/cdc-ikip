<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LowonganKerja;

class IndexController extends Controller
{
     public function perusahaanIndex()
    {
        $perusahaan = auth()->user()->perusahaan;
        $lowongan = LowonganKerja::where('perusahaan_id', $perusahaan->id)
                                 ->latest()
                                 ->paginate(20);

        return view('home.lowongan.index', compact('lowongan'));
    }
    public function show($id)
    {
        $lowongan = LowonganKerja::findOrFail($id);
        return view('home.lowongan.show', compact('lowongan'));
    }
}
