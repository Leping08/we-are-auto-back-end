<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Series
 *
 * @property integer $id
 * @property string $name
 * @property boolean $active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $races
 * @property-read $activeSeason
 */

class Season extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean'
    ];


    /**
     * @return HasMany
     */
    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }

    /**
     * Scope a query to only include one season.
     *
     * @param  Builder  $query
     * @return Builder
     * @see activeSeason
     */
    public function scopeActiveSeason(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
