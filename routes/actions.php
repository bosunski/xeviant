<?php

use Xeviant\Async\Foundation\Routing\Actions;

return function(Actions $router) {
    $router->add("App.Actions.ChangeDirectory", 'App\Actions\ChangeDirectory@handle');
    $router->add("App.Actions.GetClientId", 'App\Actions\TestAction@handle');
    $router->add("/composer/initialize/{directory}", 'App\Actions\ComposerController@initialize');
    $router->add("/code/evaluate", 'App\Actions\CodeController@runCode');

    $router->add("/package/search", 'App\Actions\PackageAction@searchPackage');
    $router->add("/package/require", 'App\Actions\PackageAction@requirePackage');
    $router->add("/package/remove", 'App\Actions\PackageAction@removePackage');
};

