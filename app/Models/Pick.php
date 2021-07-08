<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function getResultAttribute()
    {
        return $this->hasOne(Result::class, 'race_id', 'race_id')
            ->where('car_id', $this->car_id)
            ->where('race_id', $this->race_id)
            ->first();
    }
}
