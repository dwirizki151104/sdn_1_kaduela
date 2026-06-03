<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengerjaanQuiz extends Model
{
    protected $table = 'pengerjaan_quiz';
    protected $primaryKey = 'id_pengerjaan';

    protected $fillable = ['id_quiz', 'id_siswa', 'waktu_mulai', 'waktu_selesai', 'nilai'];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function jawaban()
    {
        return $this->hasMany(JawabanSiswa::class, 'id_pengerjaan', 'id_pengerjaan');
    }
}
