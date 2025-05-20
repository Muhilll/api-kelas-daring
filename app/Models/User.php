<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'nim',
        'nama',
        'id_jurusan',
        'jkel',
        'alamat',
        'no_hp',
        'email',
        'password'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function kelasDiikuti()
    {
        return $this->belongsToMany(kelas::class, 'agt_kelas', 'id_user', 'id_kelas');
    }

}