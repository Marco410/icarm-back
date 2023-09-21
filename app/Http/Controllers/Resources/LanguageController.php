<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LanguageInsertRequest;
use App\Http\Requests\LanguageUpdateRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends ApiController
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
        return $this->ok(Language::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LanguageInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Language::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        return $this->ok($language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LanguageUpdateRequest  $request
     * @param  Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageUpdateRequest $request, Language $language)
    {
        $request->validated();

        $fields = $request->all();

        $language->update($fields);

        return $this->ok($language);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        $language->delete();
        return $this->ok($language);
    }
}
