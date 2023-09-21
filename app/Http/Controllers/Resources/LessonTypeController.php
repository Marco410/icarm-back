<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LessonTypeInsertRequest;
use App\Http\Requests\LessonTypeUpdateRequest;
use App\Models\LessonType;
use Illuminate\Http\Request;

class LessonTypeController extends ApiController
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
        return $this->ok(LessonType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LessonTypeInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonTypeInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = LessonType::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  LessonType  $lessonType
     * @return \Illuminate\Http\Response
     */
    public function show(LessonType $lessonType)
    {
        return $this->ok($lessonType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LessonTypeUpdateRequest  $request
     * @param  LessonType  $lessonType
     * @return \Illuminate\Http\Response
     */
    public function update(LessonTypeUpdateRequest $request, LessonType $lessonType)
    {
        $request->validated();

        $fields = $request->all();

        $lessonType->update($fields);

        return $this->ok($lessonType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LessonType  $lessonType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonType $lessonType)
    {
        $lessonType->delete();
        return $this->ok($lessonType);
    }
}
