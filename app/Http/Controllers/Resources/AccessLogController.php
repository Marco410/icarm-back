<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AccessLogInsertRequest;
use App\Http\Requests\AccessLogUpdateRequest;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends ApiController
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
        return $this->ok(AccessLog::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AccessLogInsertRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessLogInsertRequest $request)
    {
        $request->validated();

        $fields = $request->all();

        $data = AccessLog::create($fields);

        return $this->created($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  AccessLog  $accessLog
     * @return \Illuminate\Http\Response
     */
    public function show(AccessLog $accessLog)
    {
        return $this->ok($accessLog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AccessLogUpdateRequest  $request
     * @param  AccessLog  $accessLog
     * @return \Illuminate\Http\Response
     */
    public function update(AccessLogUpdateRequest $request, AccessLog $accessLog)
    {
        $request->validated();

        $fields = $request->all();

        $accessLog->update($fields);

        return $this->ok($accessLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AccessLog  $accessLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessLog $accessLog)
    {
        $accessLog->delete();
        return $this->ok($accessLog);
    }
}
