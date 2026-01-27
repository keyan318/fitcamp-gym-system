<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'date',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
