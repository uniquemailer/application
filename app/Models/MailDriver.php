<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailDriver extends Model
{
    use HasFactory;

    protected $casts = [
        'credentials' => 'array'
    ];

    public function getSelectedColorAttribute()
    {
        if ($this->is_default) {
            return 'success';
        }
        return 'white';
    }
}
