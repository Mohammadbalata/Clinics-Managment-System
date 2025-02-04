<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = [
        'name',
        'description',
        'duration',
        'coast',
        'doctor_id',
        'room_id'
    ];

    public static function rules()
    {
        return [
            'name'       => 'required|string|max:255',
            'description'       => 'nullable|string|max:700',
            'duration'   => 'required|integer|min:5',
            'coast'   => 'required|integer|min:0',
            'doctor_id'  => 'required|exists:doctors,id',
            'room_id'  => 'required|exists:rooms,id',
        ];
    }

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
