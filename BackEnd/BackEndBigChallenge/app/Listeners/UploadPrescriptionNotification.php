<?php

namespace App\Listeners;

use App\Events\UploadPrescription;
use App\Mail\PrescriptionMail;
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

    public function handle(UploadPrescription $event): void
    {
        // grab the patient of the submission
        $user = $event->submission->patient;
        // dd($event->submission->patient);
        // send mail to his/her email 
        // dd($user[0]);
        Mail::to($user->email)->send(new PrescriptionMail($user));
    }
}
