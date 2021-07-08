<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function unique_car_classes()
    {
        return $this->car_classes->unique();
    }
}
