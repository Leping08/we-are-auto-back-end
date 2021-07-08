<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_position',
        'end_position',
        'car_id',
        'race_id',
        'car_class_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'car_id' => 'integer',
        'race_id' => 'integer',
    ];


    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}
