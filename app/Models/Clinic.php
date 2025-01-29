<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
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

    public function scopeFilter(Builder $builder, $filters)
    {

        if ($filters['office_name'] ?? false) {
            $builder->where('clinics.office_name', 'LIKE', "%{$filters['office_name']}%");
        }
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
