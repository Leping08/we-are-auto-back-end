<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

/**
 * App\Models\PotentialRace
 *
 * @property integer $id
 * @property string $title
 * @property integer $youtube_video_id
 * @property integer $series_id
 * @property integer $track_id
 * @property integer $season_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Race $series
 * @property-read Car $track
 * @property-read User $season
 */
class PotentialRace extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $fillable = ['title', 'youtube_video_id', 'series_id', 'track_id', 'season_id'];

    /**
     * @return BelongsTo
     */
    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * @return BelongsTo
     */
    public function track()
    {
        return $this->belongsTo(Track::class);
    }

    /**
     * @return BelongsTo
     */
    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
