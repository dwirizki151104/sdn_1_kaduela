<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';
    protected $primaryKey = 'id_modul';

    protected $fillable = ['id_mengajar', 'judul_modul', 'file_modul', 'tanggal_upload'];

    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class, 'id_mengajar', 'id_mengajar');
    }
}
