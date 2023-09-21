<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ClientInsertRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends ApiController
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
        return $this->ok(Client::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ClientInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Client::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return $this->ok($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ClientUpdateRequest  $request
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        $request->validated();

        $fields = $request->all();

        $client->update($fields);

        return $this->ok($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return $this->ok($client);
    }
}
