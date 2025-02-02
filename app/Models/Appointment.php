<?php

namespace App\Models;

use App\Enums\AppointmentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'procedure_id',
        'clinic_id',
        'room_id',
        'start_time',
        'end_time',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => AppointmentStatusEnum::class,
    ];

    public static function rules()
    {
        return [
            'status'     => 'in:' . implode(',', array_column(AppointmentStatusEnum::cases(), 'value')),
            'capacity'   => 'required|integer|min:1',
            'patient_id'  => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'procedure_id'  => 'required|exists:procedures,id',
            'clinic_id'  => 'required|exists:clinics,id',
            'room_id'  => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now|before:end_time',
            'end_time' => 'required|date|after:start_time',
            'cancellation_reason' => 'nullable|string',
        ];
    }


    // Relationships
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
