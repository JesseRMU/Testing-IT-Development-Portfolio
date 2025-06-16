<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Evenement extends Model
{
    /** @use HasFactory<\Database\Factories\EvenementFactory> */
    use HasFactory;
    public $timestamps = false;

    protected $table = 'evenementen';

    public function steiger(): HasOne {
        return $this->hasOne(Steiger::class, "steiger_id", "steiger_id");
    }

    public function wachthaven(): HasOne {
        return $this->hasOne(Wachthaven::class, "wachthaven_id", "wachthaven_id");
    }
}
