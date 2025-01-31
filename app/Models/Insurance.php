<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class Insurance extends Model
{
    protected $fillable = ['name', 'description','logo'];


    public function scopeFilter(Builder $builder, $filters)
    {
        if ($filters['name'] ?? false) {
            $builder->where('insurances.name', 'LIKE', "%{$filters['name']}%");
        }

        
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

    public static function rules()
    {
        return  [
            'name'       => 'required|string|max:255',
            'description'    => 'string',
            'logo' => ['image', 'max:1048576', 'dimensions:min_width=100,min_height=100']
           ];
    }
}
