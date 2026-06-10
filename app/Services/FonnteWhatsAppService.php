<?php

namespace App\Services;

use App\Models\PengerjaanQuiz;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppService
{
    public function sendQuizScore(PengerjaanQuiz $pengerjaan): void
    {
        $token = config('services.fonnte.token');
        $target = $this->normalizePhoneNumber($pengerjaan->siswa?->no_hp);

        if (blank($token) || blank($target)) {
            return;
        }

        $pengerjaan->loadMissing(['siswa', 'quiz.mengajar.mataPelajaran']);

        try {
            Http::withoutVerifying()->withHeaders([
                'Authorization' => $token,
            ])->asForm()->post(rtrim(config('services.fonnte.base_url'), '/').'/send', [
                'target' => $target,
                'message' => $this->quizScoreMessage($pengerjaan),
            ])->throw();
        } catch (\Throwable $exception) {
            Log::warning('Gagal mengirim nilai quiz via Fonnte.', [
                'id_pengerjaan' => $pengerjaan->id_pengerjaan,
                'id_siswa' => $pengerjaan->id_siswa,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function quizScoreMessage(PengerjaanQuiz $pengerjaan): string
    {
        $namaSiswa = $pengerjaan->siswa?->nama_siswa ?? 'Siswa';
        $judulQuiz = $pengerjaan->quiz?->judul_quiz ?? 'Quiz';
        $mapel = $pengerjaan->quiz?->mengajar?->mataPelajaran?->nama_mapel;
        $nilai = number_format((float) $pengerjaan->nilai, 2, ',', '.');

        return trim(implode("\n", array_filter([
            "Halo {$namaSiswa},",
            "Nilai untuk {$judulQuiz}".($mapel ? " ({$mapel})" : '')." adalah {$nilai}.",
            'Terima kasih sudah mengerjakan quiz.',
        ])));
    }

    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (blank($phoneNumber)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phoneNumber);

        if (blank($digits)) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }
}
