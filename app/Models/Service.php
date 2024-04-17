<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'template_id',
        'email_type'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function contactGroups()
    {
        return $this->belongsToMany(ContactGroup::class);
    }

    public function contactGroupsList()
    {
        foreach ($this->contactGroups as $contactGroup) {
            $rst[] = $contactGroup->name;
        }
        return $rst;
    }

    public function getTemplateNameAttribute()
    {
        if ($this->template) {
            return $this->template->name;
        }
        return null;
    }


    public function getTemplateSubjectAttribute()
    {
        if ($this->template) {
            return $this->template->subject;
        }
        return null;
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
