<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LessonSourceInsertRequest;
use App\Http\Requests\LessonSourceUpdateRequest;
use App\Models\LessonSource;
use Illuminate\Http\Request;

class LessonSourceController extends ApiController
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
        return $this->ok(LessonSource::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LessonSourceInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LessonSourceInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = LessonSource::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  LessonSource  $lessonSource
     * @return \Illuminate\Http\Response
     */
    public function show(LessonSource $lessonSource)
    {
        return $this->ok($lessonSource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LessonSourceUpdateRequest  $request
     * @param  LessonSource  $lessonSource
     * @return \Illuminate\Http\Response
     */
    public function update(LessonSourceUpdateRequest $request, LessonSource $lessonSource)
    {
        $request->validated();

        $fields = $request->all();

        $lessonSource->update($fields);

        return $this->ok($lessonSource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LessonSource  $lessonSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(LessonSource $lessonSource)
    {
        $lessonSource->delete();
        return $this->ok($lessonSource);
    }
}
