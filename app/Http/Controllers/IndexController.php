<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class IndexController extends Controller
{
    public function index(){
        $users = User::with('roles')->first();

        return view('welcome',compact('users'));
    }
}
