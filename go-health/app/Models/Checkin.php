<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'media_path',
        'user_id',
        'group_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
