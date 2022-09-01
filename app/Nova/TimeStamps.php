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
            DateTime::make('Created At')->onlyOnDetail(),
            DateTime::make('Updated At')->onlyOnDetail(),
            DateTime::make('Deleted At')->onlyOnDetail(),
        ]);
    }
}
