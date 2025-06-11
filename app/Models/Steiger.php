<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static whereNotNull(string $string)
 */
class Steiger extends Model
{
    /** @use HasFactory<\Database\Factories\SteigerFactory> */
    use HasFactory;

    public function wachthaven(): HasOne {
        return $this->hasOne(Wachthaven::class, "wachthaven_id", "wachthaven_id");
    }

    /**
     * Haalt alle evenementen op die aan deze Steiger gekoppeld zijn.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evenementen(): HasMany
    {
        return $this->hasMany(Evenement::class, 'steiger_id', 'steiger_id');
    }

    public $timestamps = false;

    protected $fillable = ['steiger_id', 'wachthaven_id', 'steiger_code', 'latitude', 'longitude'];
}
