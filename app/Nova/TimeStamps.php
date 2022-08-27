<?php

namespace App\Nova;

use App\Models\User;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Panel;

class TimeStamps
{
    /**
     * Create a timestamps panel for the resource
     *
     * @return array
     */
    public static function panel()
    {
        return new Panel('Timestamps', [
            DateTime::make('Created At')->hideFromIndex(),
            DateTime::make('Updated At')->hideFromIndex(),
            DateTime::make('Deleted At')->hideFromIndex(),
        ]);
    }
}
