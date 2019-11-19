<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use React\Promise\Promise;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        $p = yield (new Promise(function ($resolve, $reject) use ($request) {
            $resolve(response()->json($request->all() + ["name" => "Foo"], 200));
        }));

        return $p;
    }
}
