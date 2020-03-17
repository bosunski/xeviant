<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class PromiseMiddleWare
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
            $response = \React\Promise\resolve($next($request));
            if ($response instanceof PromiseInterface) {
                return $response->then(function ($re) {
                    $re->header("Brim", 'Bosunski');

                    return $re;
                });
            }
            return $response;
        });
    }
}
