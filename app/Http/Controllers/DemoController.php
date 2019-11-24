<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use React\Promise\Promise;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        return "Hello World";
    }
}
