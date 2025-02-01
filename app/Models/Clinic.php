<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use Filterable;
    protected $fillable = [
        'office_name',
        'office_address',
        'timezone',
        'secretary_number',
    ];

    public function businessHours()
    {
        return $this->hasMany(BusinessHour::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function insurances()
    {
        return $this->belongsToMany(Insurance::class, 'clinic_insurance');
    }

    public function doctors(){
        return $this->hasMany(Doctor::class);
    }

   


    public static function rules()
    {
        return  [
            'office_name'       => 'required|string|max:255',
            'office_address'    => 'required|string|max:500',
            'timezone'          => 'required|timezone',
            'secretary_number'  => 'required|string|regex:/^\+?[0-9]{7,15}$/',

            'business_hours.*.open_time'  => 'required|date_format:H:i',
            'business_hours.*.close_time'    => 'required|date_format:H:i|after:business_hours.*.open_time',

            'business_hours.*.lunch_start' => 'nullable|date_format:H:i|before:business_hours.*.lunch_end|before:business_hours.*.close_time|after:business_hours.*.open_time',
            'business_hours.*.lunch_end'   => 'nullable|date_format:H:i|after:business_hours.*.lunch_start|before:business_hours.*.close_time|after:business_hours.*.open_time',
        ];
    }
}
