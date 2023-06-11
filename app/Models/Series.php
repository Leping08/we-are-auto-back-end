<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Series
 *
 * @property integer $id
 * @property string $name
 * @property string $full_name
 * @property string $logo
 * @property string $image_url
 * @property string $website
 * @property string $description
 * @property array $settings
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Car $cars
 * @property-read Race $races
 * @property-read Race $active_season_races
 * @property-read League $leagues
 * @property-read CarClass $car_classes
 * @property-read CarClass $unique_car_classes
 * @property-read Season $seasons
 */

class Series extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'full_name',
        'logo',
        'image_url',
        'website',
        'description',
        'settings',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'settings' => 'json',
    ];


    /**
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * @return HasMany
     */
    public function races(): HasMany
    {
        return $this->hasMany(Race::class);
    }

    /**
     * @return mixed
     */
    public function active_season_races()
    {
        return $this->hasMany(Race::class)->activeSeason();
    }

    /**
     * @return HasMany
     */
    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    /**
     * @return BelongsToMany
     */
    public function car_classes(): BelongsToMany
    {
        return $this->belongsToMany(CarClass::class, Car::class);
    }

    /**
     * @return HasMany
     */
    public function potentialRaces(): HasMany
    {
        return $this->hasMany(PotentialRace::class);
    }

    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'series_tags')->withTimestamps();
    }

    /**
     * @see unique_car_classes
     */
    public function unique_car_classes()
    {
        return $this->car_classes->unique();
    }

    /**
     * @return BelongsToMany
     */
    public function users_following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow_series')->withTimestamps();
    }

    /**
     * @see seasons
     */
    public function seasons()
    {
        $seasons = $this->races->unique('season_id')->pluck('season')->sortByDesc('name')->values();
        return $seasons->map(function ($season) {
            $season['races_count'] = $season->races()->where('series_id', $this->id)->count();
            return $season;
        });
    }

     /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Storage::get($value) ?? null,
        );
    }
}
