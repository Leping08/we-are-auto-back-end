<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Race
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $race_id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $race
 * @property-read User $user
 */

class RaceProblem extends Model
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
        'description',
    ];

    /**
     * @return BelongsTo
     */
    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
