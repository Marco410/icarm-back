<?php

namespace App\Traits\Models\AccessorsMutators;

use Illuminate\Support\Str;

trait Course
{
    public function getPathCourseAttribute()
    {
        return 'courses/' . $this->link;
    }

    public function getPathImageAttribute()
    {
        return 'courses/' . $this->link . '/' . $this->image;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        $this->attributes['link'] = Str::slug($this->attributes['name'], '-');
    }

    public function setLinkAttribute($value)
    {
        $this->attributes['link'] = Str::slug(trim($this->attributes['name']), '-');
    }
}
