<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengumpulan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tugas',
        'id_agtkelas',
        'file',
        'tgl'
    ];
}
