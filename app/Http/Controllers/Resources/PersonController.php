<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PersonInsertRequest;
use App\Http\Requests\PersonUpdateRequest;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends ApiController
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
        return $this->ok(Person::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PersonInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = Person::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return $this->ok($person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PersonUpdateRequest  $request
     * @param  Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(PersonUpdateRequest $request, Person $person)
    {
        $request->validated();

        $fields = $request->all();

        $person->update($fields);

        return $this->ok($person);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        $person->delete();
        return $this->ok($person);
    }
}
