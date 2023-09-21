<?php

namespace App\Traits\Models\LocalScopes;

trait Lesson
{
    /**
     * Scope a query to only include active Lesson.
     *
     * Lesson::active()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeActive($query)
    // {
    //     return $query->where('active', 1);
    // }
    
    /**
     * Scope a query to only include Lesson model of a given type.
     *
     * Lesson::ofType('admin')->get();
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
