<?php

namespace App\Listeners;

use App\Events\UploadPrescription;
use App\Mail\PrescriptionMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class UploadPrescriptionNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(UploadPrescription $event): void
    {
        $user = $event->submission->patient;
        Mail::to($user->email)->send(new PrescriptionMail($user));
    }
}
