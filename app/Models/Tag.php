<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Tag
 *
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Series $series
 */

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    public function series()
    {
        return $this->belongsToMany(Series::class, 'series_tags')->withTimestamps();
    }
}
