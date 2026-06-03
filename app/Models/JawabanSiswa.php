<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    protected $primaryKey = 'id_jawaban';

    protected $fillable = ['id_pengerjaan', 'id_soal', 'id_pilihan'];

    public function pengerjaan()
    {
        return $this->belongsTo(PengerjaanQuiz::class, 'id_pengerjaan', 'id_pengerjaan');
    }

    public function soal()
    {
        return $this->belongsTo(SoalQuiz::class, 'id_soal', 'id_soal');
    }

    public function pilihan()
    {
        return $this->belongsTo(PilihanJawaban::class, 'id_pilihan', 'id_pilihan');
    }
}
