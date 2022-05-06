<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorInformation extends Model
{
    use HasFactory;

    const specialities = ['Cardiology', 'Dermatology', 'Emergency medicine', 'Gastroenterology', 'Oncology', 'General', 'Neurology', 'Neurosurgery', 'Gynecology', 'Opthalmology', 'Pediatrics'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
