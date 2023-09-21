<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ClientUserInsertRequest;
use App\Http\Requests\ClientUserUpdateRequest;
use App\Models\ClientUser;
use Illuminate\Http\Request;

class ClientUserController extends ApiController
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
        return $this->ok(ClientUser::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClientUserInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientUserInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = ClientUser::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  ClientUser  $clientUser
     * @return \Illuminate\Http\Response
     */
    public function show(ClientUser $clientUser)
    {
        return $this->ok($clientUser);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClientUserUpdateRequest  $request
     * @param  ClientUser  $clientUser
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUserUpdateRequest $request, ClientUser $clientUser)
    {
        $request->validated();

        $fields = $request->all();

        $clientUser->update($fields);

        return $this->ok($clientUser);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ClientUser  $clientUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientUser $clientUser)
    {
        $clientUser->delete();
        return $this->ok($clientUser);
    }
}
