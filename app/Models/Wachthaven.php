<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wachthaven extends Model
{
    /** @use HasFactory<\Database\Factories\WachthavenFactory> */
    use HasFactory;
    public $timestamps = false;

    public function object(): HasOne {
        return $this->hasOne(RWSObject::class, "object_id", "object_id");
    }

    public function steigers(): HasMany {
        return $this->hasMany(Steiger::class);
    }

    protected $table = 'wachthavens';
}
