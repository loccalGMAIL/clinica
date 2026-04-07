<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'professional_id',
        'appointment_id',
        'date',
        'content',
        'diagnosis',
        'treatment',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relaciones
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeForPatient($query, int $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeForProfessional($query, int $professionalId)
    {
        return $query->where('professional_id', $professionalId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('date', 'desc')->orderBy('id', 'desc');
    }
}
