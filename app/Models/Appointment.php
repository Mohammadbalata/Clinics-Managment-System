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
        'date',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'date' => 'date:Y-m-d',
        'status' => AppointmentStatusEnum::class,
    ];

    public static function rules($request)
    {
        return [
            'patient_id'  => 'nullable|exists:patients,id',
            'procedure_id'  => 'required|exists:procedures,id',
            'start_time' => 'required|date_format:H:i',
            'date' => 'required|date|after:now|date_format:Y-m-d',
            'patient.first_name' => $request->input('patient_id') ? 'nullable|string|min:3' : 'required|string|min:3',
            'patient.last_name' => $request->input('patient_id') ? 'nullable|string|min:3' : 'required|string|min:3',
            'patient.phone_number' =>  $request->input('patient_id') ? 'nullable|string|regex:/^\+?[0-9]{7,15}$/' : 'required|string|regex:/^\+?[0-9]{7,15}$/',
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
