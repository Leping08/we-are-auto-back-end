<?php

namespace App\Nova;

use App\Models\FollowSeries;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class Series extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Series::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'full_name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->rules('required', 'string', 'max:400'),

            Text::make('Full Name')
                ->rules('required', 'string', 'max:1000'),

            Text::make('Website')
                ->rules('string', 'max:1000')
                ->hideFromIndex(),

            Text::make('Description')
                ->rules('required', 'string', 'max:1000')
                ->hideFromIndex(),

            Text::make('Logo')
                ->rules('required', 'string', 'max:1000')
                ->hideFromIndex()
                ->hideFromDetail(),

            Text::make('Logo', function () {
                return "<div class='flex items-center'><div class='w-1/4'><img src='{$this->logo}' style='max-width: 50%;'></div><div class='w-3/4'><a target='_blank' class='no-underline dim text-primary font-bold' href='{$this->logo}'>{$this->logo}</a></div></div>";
            })
                ->asHtml()
                ->onlyOnDetail(),

            Text::make('Image Url')
                ->rules('required', 'string', 'max:1000')
                ->hideFromIndex()
                ->hideFromDetail(),

            Text::make('Image Url', function () {
                return "<div class='flex items-center'><div class='w-1/4'><img src='{$this->image_url}' style='max-width: 50%;'></div><div class='w-3/4'><a target='_blank' class='no-underline dim text-primary font-bold' href='{$this->image_url}'>{$this->image_url}</a></div></div>";
            })
                ->asHtml()
                ->onlyOnDetail(),

            TimeStamps::panel(),

            HasMany::make('Races'),
            HasMany::make('Cars'),
            BelongsToMany::make('Users Following', 'users_following', User::class),

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
