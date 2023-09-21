<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CourseInsertRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends ApiController
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->ok(Course::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CourseInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Course::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return $this->ok($course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CourseUpdateRequest  $request
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $request->validated();

        $fields = $request->all();

        $course->update($fields);

        return $this->ok($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $this->ok($course);
    }
}
