<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Letter;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        $query = Letter::query();

        // Pencarian berdasarkan nomor surat, pengirim/penerima, perihal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('pengirim_penerima', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan jenis ('masuk' atau 'keluar')
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter berdasarkan rentang tanggal surat
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_surat', [$request->start_date, $request->end_date]);
        }

        $letters = $query->latest()->paginate(10)->withQueryString();

        return view('letter.index', compact('letters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_terima_kirim' => 'required|date',
            'pengirim_penerima' => 'required|string|max:255',
            'perihal' => 'required|string',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // Maksimal 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('file_surat')) {
            $filePath = $request->file('file_surat')->store('letters', 'public');
        }

        Letter::create([
            'jenis' => $request->jenis,
            'nomor_surat' => $request->nomor_surat,
            'tanggal_surat' => $request->tanggal_surat,
            'tanggal_terima_kirim' => $request->tanggal_terima_kirim,
            'pengirim_penerima' => $request->pengirim_penerima,
            'perihal' => $request->perihal,
            'file_surat' => $filePath,
        ]);

        $jenisLabel = $request->jenis === 'masuk' ? 'Surat masuk' : 'Surat keluar';
        return redirect()->back()->with('success', "Data {$jenisLabel} berhasil disimpan!");
    }

    public function destroy(Letter $letter)
    {
        if ($letter->file_surat) {
            Storage::disk('public')->delete($letter->file_surat);
        }
        $letter->delete();
        return redirect()->back()->with('success', 'Data surat berhasil dihapus!');
    }
}
