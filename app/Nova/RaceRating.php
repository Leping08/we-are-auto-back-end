<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class RaceRating extends Resource
{
    public static $model = \App\Models\RaceRating::class;
    public static $title = 'id';
    public static $search = [
        'id',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            
            BelongsTo::make('Race'),
            BelongsTo::make('User'),
            
            Number::make('Rating')
                ->min(1)
                ->max(5)
                ->step(1)
                ->sortable(),
        ];
    }
}
