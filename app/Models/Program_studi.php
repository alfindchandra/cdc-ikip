<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program_studi extends Model
{
    protected $table = 'program_studis';

    protected $fillable = [
        'fakultas_id',
        'nama',
    ];
}
