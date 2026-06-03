<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mengajar extends Model
{
    protected $table = 'mengajar';
    protected $primaryKey = 'id_mengajar';

    protected $fillable = ['id_guru', 'id_kelas', 'id_mapel'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function modul()
    {
        return $this->hasMany(Modul::class, 'id_mengajar', 'id_mengajar');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_mengajar', 'id_mengajar');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'id_mengajar', 'id_mengajar');
    }
}
