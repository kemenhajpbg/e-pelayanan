<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\NewsReport;
use App\Services\NewsAiService;
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

    /**
     * Generate draf judul & isi berita menggunakan AI (ChatGPT / Gemini / Local Engine).
     */
    public function generateAi(Request $request, NewsAiService $aiService)
    {
        $validated = $request->validate([
            'kegiatan' => 'required|string|max:500',
            'tempat_waktu' => 'required|string|max:500',
            'garis_besar' => 'required|string|max:2000',
            'provider' => 'nullable|string|in:auto,gemini,chatgpt',
            'custom_api_key' => 'nullable|string|max:255',
        ]);

        $result = $aiService->generateNews(
            $validated['kegiatan'],
            $validated['tempat_waktu'],
            $validated['garis_besar'],
            $request->input('provider', 'auto'),
            $request->input('custom_api_key')
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
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
