<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserContextInsertRequest;
use App\Http\Requests\UserContextUpdateRequest;
use App\Models\UserContext;
use Illuminate\Http\Request;

class UserContextController extends ApiController
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
        return $this->ok(UserContext::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserContextInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserContextInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = UserContext::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  UserContext  $userContext
     * @return \Illuminate\Http\Response
     */
    public function show(UserContext $userContext)
    {
        return $this->ok($userContext);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserContextUpdateRequest  $request
     * @param  UserContext  $userContext
     * @return \Illuminate\Http\Response
     */
    public function update(UserContextUpdateRequest $request, UserContext $userContext)
    {
        $request->validated();

        $fields = $request->all();

        $userContext->update($fields);

        return $this->ok($userContext);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserContext  $userContext
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserContext $userContext)
    {
        $userContext->delete();
        return $this->ok($userContext);
    }
}
