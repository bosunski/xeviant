<?php

namespace App\Http\Controllers;

use App\Http\Requests\MultiplicationRequest;
use App\User;

class DemoController extends Controller
{
    public function index(MultiplicationRequest $request)
    {
        return User::all()->then(function ($r) {
            return response($r->pluck('name')->all())->header('X-Ban', 'Banner');
        });
    }
}
