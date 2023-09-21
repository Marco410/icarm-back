<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserProgressLessonInsertRequest;
use App\Http\Requests\UserProgressLessonUpdateRequest;
use App\Models\UserProgressLesson;
use Illuminate\Http\Request;

class UserProgressLessonController extends ApiController
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
        return $this->ok(UserProgressLesson::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserProgressLessonInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProgressLessonInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = UserProgressLesson::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  UserProgressLesson  $userProgressLesson
     * @return \Illuminate\Http\Response
     */
    public function show(UserProgressLesson $userProgressLesson)
    {
        return $this->ok($userProgressLesson);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserProgressLessonUpdateRequest  $request
     * @param  UserProgressLesson  $userProgressLesson
     * @return \Illuminate\Http\Response
     */
    public function update(UserProgressLessonUpdateRequest $request, UserProgressLesson $userProgressLesson)
    {
        $request->validated();

        $fields = $request->all();

        $userProgressLesson->update($fields);

        return $this->ok($userProgressLesson);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserProgressLesson  $userProgressLesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProgressLesson $userProgressLesson)
    {
        $userProgressLesson->delete();
        return $this->ok($userProgressLesson);
    }
}
