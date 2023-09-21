<?php

namespace App\Traits\Models\LocalScopes;

trait ViewUserRelationshipWithCourse
{
    /**
     * Scope a query to only include owner ViewUserRelationshipWithCourse.
     *
     * ViewUserRelationshipWithCourse::owner()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwner($query)
    {
        return $query->where('type', 'owner');
    }

    /**
     * Scope a query to only include subscribed ViewUserRelationshipWithCourse.
     *
     * ViewUserRelationshipWithCourse::subscribed()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubscribed($query)
    {
        return $query->where('type', 'subscribed');
    }

    /**
     * Scope a query to only include ViewUserRelationshipWithCourse model of a given type.
     *
     * ViewUserRelationshipWithCourse::ofType('owner' | 'subscribed')->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

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
