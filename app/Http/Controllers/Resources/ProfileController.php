<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ProfileInsertRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends ApiController
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
        return $this->ok(Profile::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProfileInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Profile::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        return $this->ok($profile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @param  Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, Profile $profile)
    {
        $request->validated();

        $fields = $request->all();

        $profile->update($fields);

        return $this->ok($profile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();
        return $this->ok($profile);
    }


    public function getSlList()
    {
        return Profile::select('id', 'name')->where('active', 1)->get();
    }
}
