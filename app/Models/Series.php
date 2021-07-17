<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Series
 *
 * @property integer $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Car $cars
 * @property-read Race $races
 * @property-read Race $active_season_races
 * @property-read League $leagues
 * @property-read CarClass $car_classes
 * @property-read CarClass $unique_car_classes
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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];


    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function races()
    {
        return $this->hasMany(Race::class);
    }

    public function active_season_races()
    {
        return $this->hasMany(Race::class)->activeSeason();
    }

    public function leagues()
    {
        return $this->hasMany(League::class);
    }

    public function car_classes()
    {
        return $this->belongsToMany(CarClass::class, Car::class);
    }

    /**
     * @see unique_car_classes
     */
    public function unique_car_classes()
    {
        return $this->car_classes->unique();
    }
}
