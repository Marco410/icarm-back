<?php

namespace App\Traits\Models\AccessorsMutators;

use Illuminate\Support\Str;

trait Section
{
    /**
     * Accessor name
     */
    // public function getNameAttribute()
    // {
    //     return ucwords(strtolower($this->name));
    // }

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
