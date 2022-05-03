<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'Pending';
    const STATUS_IN_PROGRESS = 'In progress';
    const STATUS_READY = 'Ready';

    // I think I don't need to specify the third parameter as it is called id
    // Should I relate this submission to PatientInformation or User?

    // if i choose to put user, i must put user_id as the second parameter and in the third, i
    // have to specify the column of the Submission i am relating to.
    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id', 'doctor_id');
    }
}
