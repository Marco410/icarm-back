<?php

namespace App\Traits\Models\AccessorsMutators;

trait User
{
    /**
     * Accessor name
     */
    // public function getNameAttribute()
    // {
    //     return ucwords(strtolower($this->name));
    // }

    /**
     * Mutators
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = trim($value);
    }

    public function setPassworAttribute($value)
    {
        $this->attributes['password'] = trim($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim($value);
    }
}
