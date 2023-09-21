<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserSessionInsertRequest;
use App\Http\Requests\UserSessionUpdateRequest;
use App\Models\UserSession;
use Illuminate\Http\Request;

class UserSessionController extends ApiController
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
        return $this->ok(UserSession::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserSessionInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserSessionInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = UserSession::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  UserSession  $userSession
     * @return \Illuminate\Http\Response
     */
    public function show(UserSession $userSession)
    {
        return $this->ok($userSession);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserSessionUpdateRequest  $request
     * @param  UserSession  $userSession
     * @return \Illuminate\Http\Response
     */
    public function update(UserSessionUpdateRequest $request, UserSession $userSession)
    {
        $request->validated();

        $fields = $request->all();

        $userSession->update($fields);

        return $this->ok($userSession);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserSession  $userSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSession $userSession)
    {
        $userSession->delete();
        return $this->ok($userSession);
    }
}
