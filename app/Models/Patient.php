<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
    ];

    public static function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',
        ];
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
