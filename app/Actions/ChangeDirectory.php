<?php

namespace App\Actions;

use Amp\Loop;
use Amp\Promise;
use App\AppClient;
use Illuminate\Support\Str;
use function Amp\call;
use function Amp\File\isdir;
use function Amp\File\scandir;

class ChangeDirectory
{
    public function handle(AppClient $client, $path)
    {
        $path = $path === "" ? "/" : $path;

//
//        Loop::run(function () use ($client, $path) {
//                $directories = yield $this->getDirectoryData($path);
//
//                foreach ($directories as $key => $directory) {
//                    if ((!yield isdir(sprintf("%s/%s", $path, $directory))) || Str::startsWith($directory, '.')) {
//                        unset($directories[$key]);
//                    }
//                }
//
//
//                $directoryData = collect($directories)->map(function($directory) use ($path) {
//                    $path = $path === "" ? sprintf("/%s", $directory) : sprintf("%s/%s", $path, $directory);
//                    return ["name" => $directory, "path" => $path];
//                })->values();
//
//                $client->sendEvent(__CLASS__,  ["root" => $path, 'children' => $directoryData->toArray()]);
//            });
    }

    protected function getDirectoryData(string $path): Promise
    {
        return call(function ($path) {
            return yield scandir($path);
        }, $path);
    }
}
