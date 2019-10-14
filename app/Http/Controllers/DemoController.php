<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use React\Promise\Promise;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        $p = new Promise(function ($resolve, $reject) use ($request) {
            $resolve(response()->json($request->all(), 200));
        });

        return $p;
    }
}
