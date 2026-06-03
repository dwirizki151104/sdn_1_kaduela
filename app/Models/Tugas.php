<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';

    protected $fillable = ['id_mengajar', 'judul_tugas', 'deskripsi', 'deadline'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class, 'id_mengajar', 'id_mengajar');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'id_tugas', 'id_tugas');
    }
}
