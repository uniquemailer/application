<?php

namespace App\Models;

use App\Casts\Payload;
use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    protected $casts = [
        'payload' => Payload::class,
    ];
}