<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LessonInsertRequest;
use App\Http\Requests\LessonUpdateRequest;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends ApiController
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
        return $this->ok(Lesson::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LessonInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Lesson::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        return $this->ok($lesson);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LessonUpdateRequest  $request
     * @param  Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(LessonUpdateRequest $request, Lesson $lesson)
    {
        $request->validated();

        $fields = $request->all();

        $lesson->update($fields);

        return $this->ok($lesson);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return $this->ok($lesson);
    }
}
