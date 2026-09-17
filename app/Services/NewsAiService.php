<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAiService
{
    /**
     * Generate judul dan narasi berita (~5 kalimat) menggunakan AI.
     *
     * @param string $kegiatan
     * @param string $tempatWaktu
     * @param string $garisBesar
     * @param string $provider 'auto', 'gemini', 'chatgpt'
     * @param string|null $customApiKey
     * @return array
     */
    public function generateNews(string $kegiatan, string $tempatWaktu, string $garisBesar, string $provider = 'auto', ?string $customApiKey = null): array
    {
        $geminiKey = $customApiKey ?: config('services.gemini.key');
        $openaiKey = $customApiKey ?: config('services.openai.key');

        // Jika user memilih spesifik atau auto
        if ($provider === 'gemini' || ($provider === 'auto' && !empty($geminiKey))) {
            $result = $this->generateWithGemini($kegiatan, $tempatWaktu, $garisBesar, $geminiKey);
            if ($result) {
                return $result;
            }
        }

        if ($provider === 'chatgpt' || ($provider === 'auto' && !empty($openaiKey))) {
            $result = $this->generateWithOpenAi($kegiatan, $tempatWaktu, $garisBesar, $openaiKey);
            if ($result) {
                return $result;
            }
        }

        // Fallback ke Generator Berita Cerdas Internal (Tanpa API Key eksternal)
        return $this->generateWithLocalEngine($kegiatan, $tempatWaktu, $garisBesar);
    }

    /**
     * Generate menggunakan Google Gemini API.
     */
    protected function generateWithGemini(string $kegiatan, string $tempatWaktu, string $garisBesar, ?string $apiKey): ?array
    {
        if (empty($apiKey)) {
            return null;
        }

        try {
            $prompt = $this->buildPrompt($kegiatan, $tempatWaktu, $garisBesar);

            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            $response = Http::timeout(12)->post($endpoint, [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'responseMimeType' => 'application/json',
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                $data = json_decode($content, true);

                if (!empty($data['judul']) && !empty($data['narasi'])) {
                    return [
                        'judul' => trim($data['judul'], " \t\n\r\0\x0B\"'"),
                        'narasi' => trim($data['narasi']),
                        'kategori' => $data['kategori'] ?? 'Kegiatan',
                        'provider' => 'Google Gemini (AI)',
                    ];
                }
            } else {
                Log::warning('Gemini API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate menggunakan OpenAI (ChatGPT) API.
     */
    protected function generateWithOpenAi(string $kegiatan, string $tempatWaktu, string $garisBesar, ?string $apiKey): ?array
    {
        if (empty($apiKey)) {
            return null;
        }

        try {
            $prompt = $this->buildPrompt($kegiatan, $tempatWaktu, $garisBesar);

            $endpoint = "https://api.openai.com/v1/chat/completions";

            $response = Http::timeout(12)->withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Anda adalah jurnalis dan humas resmi Kantor Kementerian Haji dan Umrah Republik Indonesia. Buat berita formal, baku, dan berkualitas tinggi.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $data = json_decode($content, true);

                if (!empty($data['judul']) && !empty($data['narasi'])) {
                    return [
                        'judul' => trim($data['judul'], " \t\n\r\0\x0B\"'"),
                        'narasi' => trim($data['narasi']),
                        'kategori' => $data['kategori'] ?? 'Kegiatan',
                        'provider' => 'ChatGPT / OpenAI (AI)',
                    ];
                }
            } else {
                Log::warning('OpenAI API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::warning('OpenAI API Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generator Berita Cerdas Internal (Standar Jurnalistik 5 Kalimat).
     */
    protected function generateWithLocalEngine(string $kegiatan, string $tempatWaktu, string $garisBesar): array
    {
        $kegiatanClean = rtrim($kegiatan, '. ');
        $tempatWaktuClean = rtrim($tempatWaktu, '. ');
        $garisBesarClean = rtrim($garisBesar, '. ');

        // 1. Judul Berita yang menarik
        $judul = "Kemenhaj Purbalingga Laksanakan " . ucwords($kegiatanClean);

        // 2. Pembentukan 5 Kalimat Narasi Berita Formal:
        // Kalimat 1 (Lead 5W1H):
        $k1 = "Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga sukses menyelenggarakan agenda {$kegiatanClean} yang berlangsung di {$tempatWaktuClean}.";

        // Kalimat 2 (Inti Isi / Garis Besar):
        $k2 = "Agenda ini berfokus pada {$garisBesarClean} sebagai langkah strategis dalam mengoptimalkan kualitas pelayanan dan pembinaan kepada masyarakat.";

        // Kalimat 3 (Pernyataan Resmi Pihak Kemenhaj):
        $k3 = "Dalam sambutan dan pengarahannya, pihak pimpinan menegaskan komitmen seluruh jajaran Kementerian Haji dan Umrah Purbalingga untuk terus menjaga transparansi, akuntabilitas, serta ketepatan waktu dalam setiap tahapan pelaksanaan program.";

        // Kalimat 4 (Suasana & Respons Peserta):
        $k4 = "Para peserta yang hadir tampak antusias mengikuti seluruh rangkaian acara serta berperan aktif dalam sesi koordinasi dan tanya jawab.";

        // Kalimat 5 (Penutup & Harapan ke Depan):
        $k5 = "Melalui terselenggaranya kegiatan ini, diharapkan program-program pelayanan keagamaan dan perhajian di Kabupaten Purbalingga dapat berjalan semakin prima, terstruktur, dan memberi manfaat nyata bagi umat.";

        $narasi = "{$k1} {$k2} {$k3} {$k4} {$k5}";

        // Tentukan kategori
        $kategori = 'Kegiatan';
        if (stripos($kegiatan, 'pengumuman') !== false || stripos($garisBesar, 'pengumuman') !== false) {
            $kategori = 'Pengumuman';
        } elseif (stripos($kegiatan, 'sosialisasi') !== false || stripos($kegiatan, 'edukasi') !== false || stripos($kegiatan, 'bimbingan') !== false) {
            $kategori = 'Edukasi';
        }

        return [
            'judul' => $judul,
            'narasi' => $narasi,
            'kategori' => $kategori,
            'provider' => 'Asisten AI Jurnalistik Kemenhaj',
        ];
    }

    /**
     * Susun instruksi prompt untuk LLM.
     */
    protected function buildPrompt(string $kegiatan, string $tempatWaktu, string $garisBesar): string
    {
        return <<<PROMPT
Anda adalah asisten humas dan jurnalis resmi Kantor Kementerian Haji dan Umrah Kabupaten Purbalingga.
Tolong buatkan draf rilis berita resmi berdasarkan poin-poin berikut:
1. Kegiatan: {$kegiatan}
2. Tempat dan Waktu: {$tempatWaktu}
3. Isian Garis Besar: {$garisBesar}

Instruksi:
- Buat 'judul' berita yang menarik, padat, lugas, dan sesuai gaya publikasi humas instansi pemerintah (tanpa tanda kutip di awal/akhir).
- Buat 'narasi' berita yang pas dengan panjang kurang lebih 4 sampai 5 kalimat yang utuh dan runtut (mengandung unsur 5W+1H: apa kegiatannya, di mana dan kapan, apa poin pentingnya, bagaimana jalannya acara, dan apa harapan/tujuan akhirnya).
- Gunakan bahasa Indonesia baku, formal, dan profesional.
- Tentukan 'kategori' yang sesuai ('Kegiatan', 'Pengumuman', 'Edukasi', atau 'Lainnya').

Kembalikan respon HANYA dalam bentuk objek JSON valid seperti ini:
{
  "judul": "Judul Berita",
  "narasi": "Kalimat pertama. Kalimat kedua. Kalimat ketiga. Kalimat keempat. Kalimat kelima.",
  "kategori": "Kegiatan"
}
PROMPT;
    }
}
