<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with('creator');

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $laporan = $query->latest()->paginate(20);

        return view('admin.laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('admin.laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:pkl,pelatihan,rekrutmen,kerjasama,tahunan,lainnya',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        Laporan::create($validated);

        return redirect()->route('admin.laporan.index')
                        ->with('success', 'Laporan berhasil ditambahkan');
    }

    public function show(Laporan $laporan)
    {
        return view('admin.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        return view('admin.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:pkl,pelatihan,rekrutmen,kerjasama,tahunan,lainnya',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_laporan')) {
            if ($laporan->file_laporan) {
                Storage::disk('public')->delete($laporan->file_laporan);
            }
            $validated['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
        }

        $laporan->update($validated);

        return redirect()->route('admin.laporan.index')
                        ->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy(Laporan $laporan)
    {
        if ($laporan->file_laporan) {
            Storage::disk('public')->delete($laporan->file_laporan);
        }

        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus');
    }

    public function download(Laporan $laporan)
    {
        if (!$laporan->file_laporan) {
            return back()->with('error', 'File laporan tidak tersedia');
        }

        return Storage::disk('public')->download($laporan->file_laporan);
    }

    public function publish(Laporan $laporan)
    {
        $laporan->update(['status' => 'published']);
        return back()->with('success', 'Laporan berhasil dipublikasikan');
    }
}
