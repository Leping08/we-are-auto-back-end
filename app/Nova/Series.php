<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->rules('required', 'string', 'max:400'),

            Text::make('Full Name')
                ->rules('required', 'string', 'max:1000'),

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

            HasMany::make('Cars'),
            HasMany::make('Races'),


        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
