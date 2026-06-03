<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalQuiz extends Model
{
    protected $table = 'soal_quiz';
    protected $primaryKey = 'id_soal';

    protected $fillable = ['id_quiz', 'pertanyaan', 'bobot'];

    protected $casts = [
        'bobot' => 'decimal:2',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class, 'id_soal', 'id_soal');
    }
}
