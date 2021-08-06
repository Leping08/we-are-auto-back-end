<?php

namespace App\Models;

use App\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\VideoProgress
 *
 * @property integer $id
 * @property integer $video_id
 * @property integer $user_id
 * @property integer $percentage
 * @property integer $seconds
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Video $video
 * @property-read User $user
 */

class VideoProgress extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "video_progress";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'video_id',
        'user_id',
        'percentage',
        'seconds'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'video_id' => 'integer',
        'user_id' => 'integer',
        'percentage' => 'integer',
        'seconds' => 'integer',
    ];

    protected static function booted()
    {
        parent::boot();

        static::addGlobalScope(new UserScope());
    }

    /**
     * @return BelongsTo
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
