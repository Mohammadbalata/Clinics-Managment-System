<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = [
        'clinic_id',
        'day',
        'open_time',
        'close_time',
        'lunch_start',
        'lunch_end',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
