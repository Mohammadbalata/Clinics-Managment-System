<?php

namespace App\Models;

use App\Constants\DoctorSpecialties;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Doctor extends Model
{
    use Filterable;
    protected $fillable = ['name', 'clinic_id', 'first_name', 'last_name', 'specialty', 'email', 'phone'];


    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function getFullNameAttribute()
    {
        return ucfirst(strtolower($this->first_name)) . ' ' . ucfirst(strtolower($this->last_name));
    }
}
