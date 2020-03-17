<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class TestMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed|PromiseInterface
     */
    public function handle($request, Closure $next)
    {
        $p = new Promise(function ($resolve, $reject) use ($request, $next) {
            $resolve('This is it');
        });

        return $p->then(function ($value) use ($request, $next) {
            $response = $next($request);
//            dump($response);
            return $response;
        });
    }
}
