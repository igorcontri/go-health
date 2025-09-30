<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_streak',
        'longest_streak',
        'last_checkin_date',
        'user_id',
        'group_id',
    ];

    protected $casts = [
        'last_checkin_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
