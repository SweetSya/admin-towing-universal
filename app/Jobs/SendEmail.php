<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Queueable;

    protected $mailer;
    protected $recievers;
    /**
     * Create a new job instance.
     */
    public function __construct(array $recievers, Mailable $mailer)
    {
        $this->recievers = $recievers;
        $this->mailer = $mailer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recievers)->send($this->mailer);
    }
}
