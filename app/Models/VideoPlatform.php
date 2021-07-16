<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\VideoPlatform
 *
 * @property integer $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Video $videos
 */

class VideoPlatform extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function videos()
    {
        $this->hasMany(Video::class);
    }
}
