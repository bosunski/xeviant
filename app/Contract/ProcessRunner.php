<?php

namespace App\Contract;


use React\ChildProcess\Process;

interface ProcessRunner
{
    public static function run(Process $process);
}
