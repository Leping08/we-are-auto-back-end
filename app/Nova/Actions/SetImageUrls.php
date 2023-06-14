<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class SetImageUrls extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // new logo_file, image_file
        foreach ($models as $series) {
            if ($fields->series_logo && $series->logo_file) {
                $series->logo = $series->logo_file ? Storage::disk('s3')->url($series->logo_file) : 'test';
            }

            if ($fields->series_image && $series->image_file) {
                $series->image_url = $series->image_file ? Storage::disk('s3')->url($series->image_file) : 'test';
            }

            $series->save();
        }

        return Action::message('Image URLs set!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Boolean::make('Series Logo'),
            Boolean::make('Series Image'),
        ];
    }
}
