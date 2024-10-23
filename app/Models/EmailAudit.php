<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'subject',
        'service',
        'template',
        'transaction_id',
    ];

    protected $casts = [
        'to' => 'array',
    ];

}
