<?php

namespace App\Models;

use App\Enums\RoomStatusEnum;
use App\Enums\RoomTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $fillable = [
        'name',
        'type',
        'status',
        'capacity',
        'clinic_id'
    ];

    protected $casts = [
        'type' => RoomTypeEnum::class,
        'status' => RoomStatusEnum::class,
    ];

    public static function rules($id = null)
    {
        return [
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:' . implode(',', array_column(RoomTypeEnum::cases(), 'value')),
            'status'     => 'required|in:' . implode(',', array_column(RoomStatusEnum::cases(), 'value')),
            'capacity'   => 'required|integer|min:1',
            'clinic_id'  => 'required|exists:clinics,id',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
