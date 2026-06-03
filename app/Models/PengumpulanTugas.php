<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    protected $table = 'pengumpulan_tugas';
    protected $primaryKey = 'id_pengumpulan';

    protected $fillable = ['id_tugas', 'id_siswa', 'file_jawaban', 'tanggal_kumpul', 'nilai'];

    protected $casts = [
        'tanggal_kumpul' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
