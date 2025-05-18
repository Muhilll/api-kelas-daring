<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kehadiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_absensi',
        'id_agtkelas',
        'ket',
    ];

    public function agtkelas(){
        return $this->belongsTo('App\Models\agt_kelas', 'id_agtkelas', 'id');
    }
}
