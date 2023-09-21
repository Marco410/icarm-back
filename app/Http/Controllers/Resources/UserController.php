<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserInsertRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends ApiController
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
        return $this->ok(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = User::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->ok($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $request->validated();

        $fields = $request->all();

        $user->update($fields);

        return $this->ok($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->ok($user);
    }

    public function dtList(Request $request)
    {
        return $this->ok(User::getDataFormatDT($request->all(), 'SELECT * FROM view_users', true));
    }

    public function getAllData($id)
    {
        $data = User::getAllData($id);
        if ($data != null) {
            return $this->ok($data);
        }
        return $this->notFound();
    }

    public function saveAllData(Request $request)
    {
        $data = User::saveAllData($request->toArray());

        if ($data != null) {
            return $this->ok($data);
        }

        return $this->conflict();
    }
}
