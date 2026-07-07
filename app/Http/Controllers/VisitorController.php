<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Visitor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;use Illuminate\Support\Facades\Http;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Visitor::query();

        // Pencarian berdasarkan nama, alamat, no hp, keperluan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kelompok usia
        if ($request->filled('kelompok_usia')) {
            $query->where('kelompok_usia', $request->kelompok_usia);
        }

        // Filter berdasarkan keperluan
        if ($request->filled('keperluan')) {
            $query->where('keperluan', $request->keperluan);
        }

        // Filter berdasarkan tingkat kepuasan
        if ($request->filled('tingkat_kepuasan')) {
            $query->where('tingkat_kepuasan', $request->tingkat_kepuasan);
        }

        $visitors = $query->latest()->paginate(10)->withQueryString();

        return view('visitor.index', compact('visitors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'kelompok_usia' => 'required|string',
            'keperluan' => 'required|string',
            'tingkat_kepuasan' => 'required|integer|between:1,5',
            'foto_file' => 'nullable|image|max:5120', // Maksimal 5MB
            'foto_captured' => 'nullable|string', // String base64 dari webcam
        ]);

        $fotoPath = null;

        // Tangani tangkapan foto dari webcam (base64)
        if ($request->filled('foto_captured')) {
            $imageData = $request->foto_captured;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]);

                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $imageData = base64_decode($imageData);

                    if ($imageData !== false) {
                        $fileName = 'visitor_' . time() . '_' . Str::random(10) . '.' . $type;
                        Storage::disk('public')->put('visitors/' . $fileName, $imageData);
                        $fotoPath = 'visitors/' . $fileName;
                    }
                }
            }
        }

        // Jika tidak ada foto webcam, cek jika ada file diunggah manual
        if (!$fotoPath && $request->hasFile('foto_file')) {
            $fotoPath = $request->file('foto_file')->store('visitors', 'public');
        }

        $visitor = Visitor::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'kelompok_usia' => $request->kelompok_usia,
            'keperluan' => $request->keperluan,
            'tingkat_kepuasan' => $request->tingkat_kepuasan,
            'foto_pelayanan' => $fotoPath,
        ]);

        // Kirim data ke Google Sheets Webhook jika dikonfigurasi
        $sheetWebhook = env('GOOGLE_SHEET_WEBHOOK_URL');
        if ($sheetWebhook) {
            try {
                Http::timeout(5)->post($sheetWebhook, [
                    'timestamp' => $visitor->created_at->format('Y-m-d H:i:s'),
                    'nama' => $visitor->nama,
                    'alamat' => $visitor->alamat,
                    'no_hp' => $visitor->no_hp,
                    'kelompok_usia' => $visitor->kelompok_usia,
                    'keperluan' => $visitor->keperluan,
                    'tingkat_kepuasan' => $visitor->tingkat_kepuasan,
                    'foto_url' => $fotoPath ? asset('storage/' . $fotoPath) : null,
                ]);
            } catch (\Exception $e) {
                // Log kegagalan webhook agar tidak menghalangi proses simpan data utama
                \Illuminate\Support\Facades\Log::warning('Google Sheet Webhook Failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Data pengunjung berhasil disimpan!');
    }

    public function destroy(Visitor $visitor)
    {
        if ($visitor->foto_pelayanan) {
            Storage::disk('public')->delete($visitor->foto_pelayanan);
        }
        $visitor->delete();
        return redirect()->back()->with('success', 'Data pengunjung berhasil dihapus!');
    }
}
