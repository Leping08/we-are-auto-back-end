<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Pick
 *
 * @property integer $id
 * @property integer $car_id
 * @property integer $race_id
 * @property integer $user_id
 * @property integer $league_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $race
 * @property-read Car $car
 * @property-read User $user
 * @property-read League $league
 */

class Pick extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'race_id',
        'car_id',
        'user_id',
        'league_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'race_id' => 'integer',
        'car_id' => 'integer',
        'user_id' => 'integer',
        'league_id' => 'integer',
    ];

    /**
     * Append the results to the pick model
     *
     * @var array
     */
    protected $appends = ['result'];

    /**
     * @return BelongsTo
     */
    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    /**
     * @return Model|HasOne|object|null
     */
    public function getResultAttribute()
    {
        return $this->hasOne(Result::class, 'race_id', 'race_id')
            ->where('car_id', $this->car_id)
            ->where('race_id', $this->race_id)
            ->first();
    }
}
