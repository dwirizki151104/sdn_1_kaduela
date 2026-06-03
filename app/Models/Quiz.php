<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quiz';
    protected $primaryKey = 'id_quiz';

    protected $fillable = [
        'id_mengajar',
        'judul_quiz',
        'durasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class, 'id_mengajar', 'id_mengajar');
    }

    public function soal()
    {
        return $this->hasMany(SoalQuiz::class, 'id_quiz', 'id_quiz');
    }

    public function pengerjaan()
    {
        return $this->hasMany(PengerjaanQuiz::class, 'id_quiz', 'id_quiz');
    }
}
