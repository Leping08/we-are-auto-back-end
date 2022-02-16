<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\RaceSuggestion
 *
 * @property integer $id
 * @property integer $race_id
 * @property integer $user_id
 * @property Boolean $processed
 * @property String $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $race
 * @property-read User $user
 */

class RaceSuggestion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'race_id',
        'user_id',
        'processed',
        'data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'race_id' => 'integer',
        'user_id' => 'integer',
        'processed' => 'boolean',
        'data' => 'array'
    ];

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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
