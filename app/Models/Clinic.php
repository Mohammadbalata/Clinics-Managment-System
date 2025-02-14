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

   
}
