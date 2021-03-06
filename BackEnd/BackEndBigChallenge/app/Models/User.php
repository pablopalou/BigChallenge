<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birth',
    ];

    // HasOne relation can be null, it's not required to have relation row in database. Just check it while you get it.
    // A doctor can also be a patient, so every time a doctor register, we will register as a patient too.
    public function patientInformation():HasOne
    {
        return $this->hasOne(PatientInformation::class, 'user_id');
    }

    public function doctorInformation():HasOne
    {
        return $this->hasOne(DoctorInformation::class, 'user_id');
    }

    // if we have a doctor, we will have all the submissions taken by the doctor
    // a doctor has many submissions, with the doctor_id column on table Submission and I want to
    // relate it with column id on User table.
    public function submissionsTaken():HasMany
    {
        return $this->hasMany(Submission::class, 'doctor_id');
    }

    // a patient has lot of submissions made
    public function submissionsMade():HasMany
    {
        return $this->hasMany(Submission::class, 'patient_id');
    }
}
