<?php

namespace App\Http\Controllers;

use App\Models\UserContext;
use App\Models\ViewUserRelationshipWithClient;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function index(Request $request)
    {
        return $this->ok(User::with('iglesia')->get());
    }

    public function test(Request $request)
    {
       return $request->otra;
    }
}
