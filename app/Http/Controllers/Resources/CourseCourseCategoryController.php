<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CourseCourseCategoryInsertRequest;
use App\Http\Requests\CourseCourseCategoryUpdateRequest;
use App\Models\CourseCourseCategory;
use Illuminate\Http\Request;

class CourseCourseCategoryController extends ApiController
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
        return $this->ok(CourseCourseCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CourseCourseCategoryInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseCourseCategoryInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = CourseCourseCategory::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseCourseCategory  $courseCourseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCourseCategory $courseCourseCategory)
    {
        return $this->ok($courseCourseCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CourseCourseCategoryUpdateRequest  $request
     * @param  CourseCourseCategory  $courseCourseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(CourseCourseCategoryUpdateRequest $request, CourseCourseCategory $courseCourseCategory)
    {
        $request->validated();

        $fields = $request->all();

        $courseCourseCategory->update($fields);

        return $this->ok($courseCourseCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseCourseCategory  $courseCourseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCourseCategory $courseCourseCategory)
    {
        $courseCourseCategory->delete();
        return $this->ok($courseCourseCategory);
    }
}
