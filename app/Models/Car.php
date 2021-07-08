<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'series_id',
        'car_class_id',
        'image'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'series_id' => 'integer',
        'car_class_id' => 'integer',
    ];


    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function races()
    {
        return $this->belongsToMany(Race::class, 'results');
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function car_class()
    {
        return $this->belongsTo(CarClass::class);
    }
}
