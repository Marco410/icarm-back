<?php

namespace App\Traits\Models\LocalScopes;

trait Section
{
    /**
     * Scope a query to only include active Section.
     *
     * Section::active()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('active', 1);
    // }
    
    /**
     * Scope a query to only include Section model of a given type.
     *
     * Section::ofType('admin')->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeOfType($query, $type)
    // {
    //    return $query->where('type', $type);
    // }
}
