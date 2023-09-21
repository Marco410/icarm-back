<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SectionInsertRequest;
use App\Http\Requests\SectionUpdateRequest;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends ApiController
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
        return $this->ok(Section::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SectionInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectionInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Section::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        return $this->ok($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SectionUpdateRequest  $request
     * @param  Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(SectionUpdateRequest $request, Section $section)
    {
        $request->validated();

        $fields = $request->all();

        $section->update($fields);

        return $this->ok($section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return $this->ok($section);
    }
}
