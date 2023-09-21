<?php

namespace App\Traits\Models\AccessorsMutators;

trait Person
{
    /**
     * Accessor name
     */
    public function getFullNameAttribute()
    {
        return implode(' ',[$this->attributes['name'], $this->attributes['lastname'], $this->attributes['second_surname']]);
    }

    /**
     * Mutators
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = trim($value);
    }

    public function setSecondSurnameAttribute($value)
    {
        $this->attributes['second_surname'] = trim($value);
    }

    public function setBirthdateAttribute($value)
    {
        if (strlen($value) > 10) {
            $this->attributes['birthdate'] = substr($value, 0, 10);
        }
    }
}
