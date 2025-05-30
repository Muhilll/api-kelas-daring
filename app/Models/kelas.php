<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'id_user',
        'deskripsi',
        'jenis'
    ];

    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user', 'id');
    }

    public function anggota()
    {
        return $this->belongsToMany(User::class, 'agt_kelas', 'id_kelas', 'id_user');
    }
    
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

}
