<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\FunctionProfileInsertRequest;
use App\Http\Requests\FunctionProfileUpdateRequest;
use App\Models\FunctionProfile;
use Illuminate\Http\Request;

class FunctionProfileController extends ApiController
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
        return $this->ok(FunctionProfile::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\FunctionProfileInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FunctionProfileInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = FunctionProfile::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  FunctionProfile  $functionProfile
     * @return \Illuminate\Http\Response
     */
    public function show(FunctionProfile $functionProfile)
    {
        return $this->ok($functionProfile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FunctionProfileUpdateRequest  $request
     * @param  FunctionProfile  $functionProfile
     * @return \Illuminate\Http\Response
     */
    public function update(FunctionProfileUpdateRequest $request, FunctionProfile $functionProfile)
    {
        $request->validated();

        $fields = $request->all();

        $functionProfile->update($fields);

        return $this->ok($functionProfile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  FunctionProfile  $functionProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(FunctionProfile $functionProfile)
    {
        $functionProfile->delete();
        return $this->ok($functionProfile);
    }
}
