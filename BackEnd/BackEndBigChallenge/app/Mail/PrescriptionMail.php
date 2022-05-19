<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrescriptionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userPatient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $userPatient)
    {
        $this->userPatient = $userPatient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.prescription.mail', [
            'userPatient' => $this->userPatient,
        ]);
    }
}
