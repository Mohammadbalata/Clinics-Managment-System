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
        'clinic_id'
    ];

    public static function rules()
    {
        return [
            'name'       => 'required|string|max:255',
            'description'       => 'nullable|string|max:700',
            'duration'   => 'required|integer|min:5',
            'coast'   => 'required|integer|min:0',
            'clinic_id'  => 'required|exists:clinics,id',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id')->withDefault();
    }
}
