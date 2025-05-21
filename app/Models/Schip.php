<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schip extends Model
{
    /** @use HasFactory<\Database\Factories\SchipFactory> */
    use HasFactory;
    protected $table = 'evenementen';
}
