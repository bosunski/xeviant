<?php

use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use React\Promise\Promise;

require "vendor/autoload.php";

class Ace {
    public function handle($request, Closure $next) {
        new Promise(function ($resolve, $reject) use ($request, $next) {
                $next($request . "Ace");
        });
    }
}

class BAce {
    public function handle($request, Closure $next) {
        return $next($request . "Brrr");
    }
}
$pipeline = new Pipeline(new Container);

$middlewares = [
    Ace::class,
    BAce::class
];

$pipeline->send("Bosun")
    ->through($middlewares)
    ->then(function ($passable) {
        dump($passable);
    });
