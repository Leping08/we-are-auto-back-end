<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PotentialRace extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PotentialRace::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $json = [
            "name" => $this->title,
            "track_id" => $this->track_id ?? null,
            "series_id" => $this->series_id ?? null,
            "season_id" => $this->season_id ?? null
        ];

        return [
            ID::make()->sortable(),
            Text::make('Title'),
            Text::make('Youtube Video Id')->hideFromDetail(),
            Text::make('Youtube Video Id', function () {
                return view('partials.link', [
                    'link' => "https://www.youtube.com/watch?v=".$this->youtube_video_id,
                    'text' => $this->youtube_video_id,
                    'new_tab' => true
                ])->render();
            })->asHtml()->onlyOnDetail(),
            BelongsTo::make('Series', 'series', \App\Nova\Series::class)->hideFromIndex(),
            BelongsTo::make('Track', 'track', \App\Nova\Track::class)->hideFromIndex(),
            BelongsTo::make('Season', 'season', \App\Nova\Season::class)->hideFromIndex(),
            Text::make('Create Race', function () use ($json) {
                return view('partials.link', [
                    'link' => "/admin/resources/races/new?viaRelationship=".json_encode($json),
                    'text' => 'Create Race',
                    'new_tab' => false
                ])->render();
            })->asHtml()->onlyOnDetail(),
            DateTime::make('Created At')->onlyOnDetail(),
            DateTime::make('Updated At')->onlyOnDetail(),
            DateTime::make('Deleted At')->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
