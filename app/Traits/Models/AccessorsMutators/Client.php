<?php

namespace App\Traits\Models\AccessorsMutators;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait Client
{
    public function getUrlStorageAttribute()
    {
        return  Storage::disk('public')->url('clients/' . $this->link);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        $this->attributes['link'] = Str::slug(trim($this->attributes['name']), '-');
    }

    public function setLinkAttribute($value)
    {
        $this->attributes['link'] = Str::slug(trim($this->attributes['name']), '-');
    }
}
