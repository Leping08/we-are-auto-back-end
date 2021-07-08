<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'series_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'series_id' => 'integer'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function current_picks()
    {
        return $this->hasMany(CurrentPick::class);
    }
}
