<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CourseCategoryInsertRequest;
use App\Http\Requests\CourseCategoryUpdateRequest;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends ApiController
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
        return $this->ok(CourseCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CourseCategoryInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseCategoryInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = CourseCategory::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(CourseCategory $courseCategory)
    {
        return $this->ok($courseCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CourseCategoryUpdateRequest  $request
     * @param  CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(CourseCategoryUpdateRequest $request, CourseCategory $courseCategory)
    {
        $request->validated();

        $fields = $request->all();

        $courseCategory->update($fields);

        return $this->ok($courseCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseCategory  $courseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseCategory $courseCategory)
    {
        $courseCategory->delete();
        return $this->ok($courseCategory);
    }
}
