<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Insurance extends Model
{
    use Filterable;

    protected $fillable = ['name', 'description', 'logo'];




    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_insurance');
    }



    public static function uploadImage(Request $request)
    {
        if (!$request->hasFile('logo')) {
            return;
        }
        $file = $request->file('logo');
        $path =  $file->store('uploads', 'public');
        return $path;
    }

}
