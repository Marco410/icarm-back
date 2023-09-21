<?php

namespace App\Http\Controllers\Tests;

use App\Models\User;
use App\Models\ViewClientInfo;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExampleController extends ApiController
{
    public function index()
    {
        return $this->ok(User::find(1));
    }

    public function error_responses()
    {
        //return $this->badRequest('eeeee');
        //return $this->errorResponse(404.1);
        //$this->httpException(404);
        //$this->httpException(404, 'El recuuurso no fue encontrado');
        //$this->httpException(404.1);
        //$this->httpException(404.1, 'El recursooooooooo no fue encontraaaaaaaaaaado');
        throw new HttpException(422, 'throw new HttpException');
        //return $this->ok(User::all());
    }

    public function client_info()
    {
        return $this->ok(ViewClientInfo::find(1));
    }
}
