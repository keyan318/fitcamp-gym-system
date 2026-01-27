<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    // Add these fields to allow mass assignment
    protected $fillable = [
    'member_id',
    'full_name',
    'facebook_name',
    'email',
    'membership_type',
    'additional_membership',
    'valid_days',
    'start_date',
    'end_date',
    'id_photo',
    'status',

];

}
