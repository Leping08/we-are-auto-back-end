<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Video
 *
 * @property integer $id
 * @property string $video_id
 * @property integer $video_platform_id
 * @property integer $race_id
 * @property integer $start_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $race
 * @property-read VideoPlatform $platform
 */

class Video extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id',
        'video_platform_id',
        'start_time',
        'race_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'video_id' => 'string',
        'video_platform_id' => 'integer',
        'start_time' => 'integer',
        'race_id' => 'integer',
    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function platform()
    {
        return $this->belongsTo(VideoPlatform::class);
    }
}
