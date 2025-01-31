<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{

    protected $fillable = [
        'clinic_id', 'question', 'answer'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }


    public static function rules()
    {
        return  [
            'question'       => 'required|string|max:255',
            'answer'    => 'required|string|max:500',
            ];
    }

}
