<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\FunctionInsertRequest;
use App\Http\Requests\FunctionUpdateRequest;
use App\Models\FunctionModel;
use Illuminate\Http\Request;

class FunctionController extends ApiController
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
        return $this->ok(FunctionModel::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FunctionInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FunctionInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = FunctionModel::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  FunctionModel  $function
     * @return \Illuminate\Http\Response
     */
    public function show(FunctionModel $function)
    {
        return $this->ok($function);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FunctionUpdateRequest  $request
     * @param  FunctionModel  $function
     * @return \Illuminate\Http\Response
     */
    public function update(FunctionUpdateRequest $request, FunctionModel $function)
    {
        $request->validated();

        $fields = $request->all();

        $function->update($fields);

        return $this->ok($function);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FunctionModel  $function
     * @return \Illuminate\Http\Response
     */
    public function destroy(FunctionModel $function)
    {
        $function->delete();
        return $this->ok($function);
    }
}
