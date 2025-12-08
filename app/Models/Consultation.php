<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'consulted_at',
        'reason',
        'notes',
        'diagnosis',
        'plan',
        'weight',
        'height',
        'blood_pressure',
        'heart_rate',
        'resp_rate',
        'temperature',
        'created_by',
    ];

    protected $casts = [
        'consulted_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}


