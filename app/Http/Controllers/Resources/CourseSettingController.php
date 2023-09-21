<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CourseSettingInsertRequest;
use App\Http\Requests\CourseSettingUpdateRequest;
use App\Models\CourseSetting;
use Illuminate\Http\Request;

class CourseSettingController extends ApiController
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
        return $this->ok(CourseSetting::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CourseSettingInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseSettingInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = CourseSetting::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseSetting  $courseSetting
     * @return \Illuminate\Http\Response
     */
    public function show(CourseSetting $courseSetting)
    {
        return $this->ok($courseSetting);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CourseSettingUpdateRequest  $request
     * @param  CourseSetting  $courseSetting
     * @return \Illuminate\Http\Response
     */
    public function update(CourseSettingUpdateRequest $request, CourseSetting $courseSetting)
    {
        $request->validated();

        $fields = $request->all();

        $courseSetting->update($fields);

        return $this->ok($courseSetting);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseSetting  $courseSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseSetting $courseSetting)
    {
        $courseSetting->delete();
        return $this->ok($courseSetting);
    }
}
