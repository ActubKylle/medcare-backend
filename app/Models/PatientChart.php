<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientChart extends Model
{
    use HasFactory;

    // Define the table name explicitly to match your migration
    protected $table = 'patient_chart';

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'dob',
        'gender',
        'address',
        'spouse',
        'diagnosis',
        'procedure',
        'history',
        'prescription',
        'doctor_id'
    ];

    protected $casts = [
        'dob' => 'date',
            'age' => 'integer'

    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
