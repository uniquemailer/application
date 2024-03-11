<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];  

    public function contact_group()
    {
        return $this->belongsTo(ContactGroup::class);
    }

    public function getContactNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


/*     protected function contactGroup(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
        );
    } */
}
