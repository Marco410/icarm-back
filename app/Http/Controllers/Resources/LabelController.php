<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LabelInsertRequest;
use App\Http\Requests\LabelUpdateRequest;
use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends ApiController
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
        return $this->ok(Label::all());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LabelInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LabelInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Label::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return $this->ok($label);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LabelUpdateRequest  $request
     * @param  Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(LabelUpdateRequest $request, Label $label)
    {
        $request->validated();

        $fields = $request->all();

        $label->update($fields);

        return $this->ok($label);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $label->delete();
        return $this->ok($label);
    }

    public function dtList(Request $request)
    {
        return $this->ok(Label::getDataFormatDT($request->all(), 'SELECT * FROM view_list_labels', true));
    }

    public function getAllData($id)
    {
        $data = Label::getAllData($id);
        if ($data != null) {
            return $this->ok($data);
        }
        return $this->notFound();
    }

    public function saveAllData(Request $request)
    {
        $data = Label::saveAllData($request);

        if ($data != null) {
            return $this->ok($data);
        }

        return $this->conflict();
    }
}
