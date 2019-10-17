<?php

namespace App\Actions;

use App\ComposerProcessRunner;
use App\Notebook;
use App\Scripts\RemoveComposerPackage;
use App\Scripts\RequireComposerPackage;
use Clue\React\Buzz\Browser;
use Clue\React\Packagist\Api\Client;
use Exception;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Result;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Xeviant\Async\Foundation\WebSocket\Session;
use function React\Promise\all;

class PackageAction
{
    public function searchPackage($packageName) {
        $browser = new Browser(app(LoopInterface::class));
        $client = new Client($browser);

        try {
            $packages = [];

            $client->search($packageName, ["per_page" => 10])->then(function($results) use(&$packages, $client) {
                collect($results)->each(function(Result $package) use (&$packages, $client) {
                    $packages[] = $client->get($package->getName());
                });

                all($packages)->then(function(array $packages) {
                    $data = collect($packages)->map(function (Package $package) {
                        $versions = collect($package->getVersions())->map(function (Package\Version $version) {
                            return $version->getVersion();
                        })->toArray();

                        return ["installed" => false, "installing" => false, "name" => $package->getName(), "versions" => array_values($versions)];
                    })->toArray();
                    Session::send(json_encode(["event" => "package.result", "data" => $data ]));
                });

                if (!$results || count($results) === 0) {
                    Session::send(json_encode(["event" => "package.result", "data" => []]));
                }
            });

        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            Session::send(json_encode(["event" => "package.result", "data" => []]));
        }
    }

    public function requirePackage($notebookPath, $packageName)
    {
        $script = new RequireComposerPackage($packageName, new Notebook($notebookPath));
        $process = new Process($script);
        ComposerProcessRunner::requirePackage($packageName, $process);
    }

    public function removePackage($notebookPath, $packageName)
    {
        $script = new RemoveComposerPackage($packageName, new Notebook($notebookPath));
        $process = new Process($script);
        ComposerProcessRunner::removePackage($packageName, $process);
    }
}
