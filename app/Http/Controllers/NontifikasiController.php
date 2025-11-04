<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()
                                   ->latest()
                                   ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        $notifikasi->markAsRead();
        
        if ($notifikasi->link) {
            return redirect($notifikasi->link);
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->notifikasi()
                     ->where('is_read', false)
                     ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }
}

// app/Http/Controllers/CatatanController.php
namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index()
    {
        $catatan = auth()->user()->catatan()
                               ->orderBy('is_pinned', 'desc')
                               ->latest()
                               ->paginate(20);

        return view('catatan.index', compact('catatan'));
    }

    public function create()
    {
        return view('catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Catatan::create($validated);

        return redirect()->route('catatan.index')
                        ->with('success', 'Catatan berhasil ditambahkan');
    }

    public function show(Catatan $catatan)
    {
        return view('catatan.show', compact('catatan'));
    }

    public function edit(Catatan $catatan)
    {
        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, Catatan $catatan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
        ]);

        $catatan->update($validated);

        return redirect()->route('catatan.index')
                        ->with('success', 'Catatan berhasil diperbarui');
    }

    public function destroy(Catatan $catatan)
    {
        $catatan->delete();

        return back()->with('success', 'Catatan berhasil dihapus');
    }

    public function togglePin(Catatan $catatan)
    {
        $catatan->update(['is_pinned' => !$catatan->is_pinned]);

        return back()->with('success', 'Status pin berhasil diubah');
    }
}

