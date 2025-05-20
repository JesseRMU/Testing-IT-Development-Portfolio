<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RWSObject extends Model
{
    protected $table = 'rws_objecten';

    /**
     * @return HasMany
     */
    public function wachthavens(): HasMany
    {
        return $this->hasMany(Wachthaven::class);
    }
}
