<?php

namespace App\Traits\Models\LocalScopes;

trait Client
{
    /**
     * Scope a query to only include active Client.
     *
     * Client::active()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    
    /**
     * Scope a query to only include Client model of a given type.
     *
     * Client::ofType('admin')->get();
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
