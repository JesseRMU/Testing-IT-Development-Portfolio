<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wachthaven extends Model
{
    /** @use HasFactory<\Database\Factories\ObjectFactory> */
    use HasFactory;

    protected $table = 'objecten';
}
