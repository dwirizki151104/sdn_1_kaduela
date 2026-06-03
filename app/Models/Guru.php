<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = ['id_user', 'nip', 'nama_guru', 'jenis_guru', 'no_hp', 'alamat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'id_wali_kelas', 'id_guru');
    }

    public function mengajar()
    {
        return $this->hasMany(Mengajar::class, 'id_guru', 'id_guru');
    }
}
