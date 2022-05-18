<?php

namespace App\Listeners;

use App\Events\UploadPrescription;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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

    /**
     * Handle the event.
     *
     * @param  \App\Events\UploadPrescription  $event
     * @return void
     */
    public function handle(UploadPrescription $event)
    {
        // grab the patient of the submission
        $user = User::find($event->submission->patient);
        // send mail to his/her email 
        Mail::to($user->email)->send(new PrescriptionMail($user));
    }
}
