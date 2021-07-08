<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Post
 *
 * @property integer $id
 * @property integer $author_id
 * @property string $title
 * @property string $text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read User $user
 * @property $activeSeason
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $comments
 */

class Race extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'length',
        'series_id',
        'track_id',
        'season_id',
        'starts_at',
        'finishes_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'series_id' => 'integer',
        'track_id' => 'integer',
        'season_id' => 'integer',
        'starts_at' => 'datetime',
        'finishes_at' => 'datetime',
    ];


    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function picks()
    {
        return $this->hasMany(Pick::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'results');
    }

    public function user_results(User $user)
    {
        return $this->hasMany(Pick::class)
            ->where('user_id', '=', $user->id)
            ->with(['car.car_class', 'car.series', 'car.results' => function ($query) {
                return $query->where('race_id', '=', $this->id);
            }])
            ->get()
            ->pluck('car')
            ->map(function ($pick) {
                $pick->result = $pick->results ? $pick->results->first() : null;
                unset($pick->results);
                return $pick;
            })
            ->all();
    }

    /**
     * Scope a query to only include one season.
     *
     * @see activeSeason
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveSeason($query)
    {
        $activeSeason = Season::activeSeason()->first();
        return $query->where('season_id', $activeSeason->id);
    }
}
