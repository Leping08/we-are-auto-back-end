<?php

namespace App\Mail;

use App\Models\RaceProblem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRaceProblem extends Mailable
{
    use Queueable, SerializesModels;

    public $raceProblem;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RaceProblem $raceProblem)
    {
        $this->raceProblem = $raceProblem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->with([
                        'raceProblem' => $this->raceProblem
                    ])
                    ->subject('New Race Problem Reported')
                    ->markdown('emails.race-problem-reported');
    }
}
