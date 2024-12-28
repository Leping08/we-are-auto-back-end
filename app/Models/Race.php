<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Race
 *
 * @property integer $id
 * @property string $name
 * @property string $length
 * @property integer $series_id
 * @property integer $track_id
 * @property integer $season_id
 * @property Carbon $starts_at
 * @property Carbon $finishes_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property bool $new
 * @property-read Series $series
 * @property-read Track $track
 * @property-read Season $season
 * @property-read RaceProblems $race_problems
 * @property-read Video $videos
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
        'boolean' => 'new',
        'average_rating' => 'float'
    ];

    protected $appends = ['average_rating'];

    /**
     * @return BelongsTo
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * @return BelongsTo
     */
    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    /**
     * @return BelongsTo
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * @return HasMany
     */
    public function picks(): HasMany
    {
        return $this->hasMany(Pick::class);
    }

    /**
     * @return HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * @return HasMany
     */
    public function race_problems(): HasMany
    {
        return $this->hasMany(RaceProblem::class);
    }

    /**
     * @return BelongsToMany
     */
    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'results');
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * @return HasMany
     */
    public function problems(): HasMany
    {
        return $this->hasMany(RaceProblem::class);
    }

    /**
     * @return HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(RaceRating::class);
    }

    /**
     * @return float
     */
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0.0;
    }

    /**
     * @param  int  $userId
     * @return int|null
     */
    public function getUserRating($userId): ?int
    {
        return $this->ratings()->where('user_id', $userId)->value('rating');
    }

    /**
     * @param  User  $user
     * @return array
     */
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
     * @param  Builder  $query
     * @return Builder
     * @see activeSeason
     */
    public function scopeActiveSeason(Builder $query): Builder
    {
        $activeSeason = Season::activeSeason()->first();
        return $query->where('season_id', $activeSeason->id);
    }

    public function getAverageRatingAttribute()
    {
        return Cache::remember(
            "race.{$this->id}.average_rating",
            Carbon::now()->addDays(7),
            fn() => $this->ratings()->avg('rating') ?? 0
        );
    }

    public function clearRatingCache()
    {
        Cache::forget("race.{$this->id}.average_rating");
    }
}
