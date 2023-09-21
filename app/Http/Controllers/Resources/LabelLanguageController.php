<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LabelLanguageInsertRequest;
use App\Http\Requests\LabelLanguageUpdateRequest;
use App\Models\LabelLanguage;
use Illuminate\Http\Request;

class LabelLanguageController extends ApiController
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
        return $this->ok(LabelLanguage::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LabelLanguageInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabelLanguageInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = LabelLanguage::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  LabelLanguage  $labelLanguage
     * @return \Illuminate\Http\Response
     */
    public function show(LabelLanguage $labelLanguage)
    {
        return $this->ok($labelLanguage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LabelLanguageUpdateRequest  $request
     * @param  LabelLanguage  $labelLanguage
     * @return \Illuminate\Http\Response
     */
    public function update(LabelLanguageUpdateRequest $request, LabelLanguage $labelLanguage)
    {
        $request->validated();

        $fields = $request->all();

        $labelLanguage->update($fields);

        return $this->ok($labelLanguage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LabelLanguage  $labelLanguage
     * @return \Illuminate\Http\Response
     */
    public function destroy(LabelLanguage $labelLanguage)
    {
        $labelLanguage->delete();
        return $this->ok($labelLanguage);
    }
}
