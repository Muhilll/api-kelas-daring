<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agt_kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kelas',
        'id_user'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'id_user', 'id');
    }
}
