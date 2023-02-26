<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SeriesTag
 *
 * @property integer $id
 * @property string $series_id
 * @property string $tag_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Series $series
 * @property-read Tag $tag
 */

class SeriesTag extends Pivot
{
    use HasFactory, SoftDeletes;

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
