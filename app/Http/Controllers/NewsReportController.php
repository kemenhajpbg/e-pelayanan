<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\NewsReport;
use Illuminate\Support\Facades\Storage;

class NewsReportController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsReport::query();

        // Pencarian berdasarkan judul, narasi, kategori
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('narasi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $newsReports = $query->latest()->paginate(10)->withQueryString();

        return view('news.index', compact('newsReports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal_tayang' => 'required|date',
            'link_berita' => 'nullable|url|max:255',
            'kategori' => 'required|string',
            'narasi' => 'required|string',
            'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // Maksimal 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $filePath = $request->file('file_dokumen')->store('news', 'public');
        }

        NewsReport::create([
            'judul' => $request->judul,
            'tanggal_tayang' => $request->tanggal_tayang,
            'link_berita' => $request->link_berita,
            'kategori' => $request->kategori,
            'narasi' => $request->narasi,
            'file_dokumen' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Laporan berita tayang berhasil disimpan!');
    }

    public function destroy(NewsReport $newsReport)
    {
        if ($newsReport->file_dokumen) {
            Storage::disk('public')->delete($newsReport->file_dokumen);
        }
        $newsReport->delete();
        return redirect()->back()->with('success', 'Laporan berita tayang berhasil dihapus!');
    }
}
