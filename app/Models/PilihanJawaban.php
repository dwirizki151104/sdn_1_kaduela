<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    protected $table = 'pilihan_jawaban';
    protected $primaryKey = 'id_pilihan';

    protected $fillable = ['id_soal', 'opsi', 'isi_pilihan', 'is_benar'];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalQuiz::class, 'id_soal', 'id_soal');
    }
}
