<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CourseUserInsertRequest;
use App\Http\Requests\CourseUserUpdateRequest;
use App\Models\CourseUser;
use Illuminate\Http\Request;

class CourseUserController extends ApiController
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
        return $this->ok(CourseUser::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CourseUserInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseUserInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = CourseUser::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function show(CourseUser $courseUser)
    {
        return $this->ok($courseUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CourseUserUpdateRequest  $request
     * @param  CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function update(CourseUserUpdateRequest $request, CourseUser $courseUser)
    {
        $request->validated();

        $fields = $request->all();

        $courseUser->update($fields);

        return $this->ok($courseUser);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CourseUser  $courseUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseUser $courseUser)
    {
        $courseUser->delete();
        return $this->ok($courseUser);
    }
}
