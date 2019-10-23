<?php

namespace App\Actions;

use App\LocalCodeRunner;
use App\Notebook;
use App\Objects\PHPCode;
use App\Scripts\RunPHPCode;
use React\ChildProcess\Process;

class CodeController
{
    public function runCode($code, $notebookPath, $notebookId)
    {
        $script = new RunPHPCode(new PHPCode($code), new Notebook($notebookPath));
        $process = new Process((string) $script);
        LocalCodeRunner::run($process, $notebookId);
    }
}
