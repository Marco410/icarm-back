<?php

namespace App\Traits\Models\LocalScopes;

trait ViewUserProgressLesson
{
    /**
     * Scope a query to only include ViewUserRelationshipWithCourse model of a given type.
     *
     * ViewUserRelationshipWithCourse::IdsUserCourse($user_id, $course_id)->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $user_id
     * @param  mixed  $course_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIdsUserCourse($query, $user_id, $course_id)
    {
        return $query->where('user_id', $user_id)->where('course_id', $course_id);
    }
}
