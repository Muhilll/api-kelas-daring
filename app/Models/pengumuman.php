<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengumuman extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_kelas',
        'nama',
        'desk',
        'file'
    ];
}
