<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, hasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // HasOne relation can be null, it's not required to have relation row in database. Just check it while you get it.
    // A doctor can also be a patient, so every time a doctor register, we will register as a patient too.
    public function patientInformation()
    {
        return $this->hasOne(PatientInformation::class, 'user_id');
    }

    public function doctorInformation()
    {
        return $this->hasOne(DoctorInformation::class, 'user_id');
    }

    // if we have a doctor, we will have all the submissions taken by the doctor
    // a doctor has many submissions, with the doctor_id column on table Sumbission and I want to
    // relate it with column id on User table.
    public function submissionsTaken()
    {
        return $this->hasMany(Submission::class, 'doctor_id');
    }

    // a patient has lot of submissions made
    public function submissionsMade()
    {
        return $this->hasMany(Submission::class, 'patient_id');
    }
}
