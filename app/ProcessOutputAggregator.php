<?php

namespace App;

use App\Objects\PHPEvaluationResult;
use React\ChildProcess\Process;
use Xeviant\Async\Foundation\WebSocket\Session;

class ProcessOutputAggregator
{
    /**
     * @var string
     */
    private $output;

    private $timedOut = false;
    /**
     * @var Process
     */
    private $process;

    private $notebookId;

    /**
     * ProcessOutputAggregator constructor.
     * @param Process $process
     * @param $notebookId
     */
    public function __construct(Process $process, $notebookId)
    {
        $this->process = $process;
        $this->notebookId = $notebookId;
    }

    public function outputHandler($buffer)
    {
        $this->output .= $buffer;
    }

    public function exitHandler($exitCode)
    {
        if ($exitCode === 0) {
            Session::sendEvent(
                'Code.Evaluated.'.$this->notebookId,
                (string) PHPEvaluationResult::make($this->output ?? "")
            );
        } else {
            Session::sendEvent(
                'Code.Evaluated.'.$this->notebookId,
                (string)PHPEvaluationResult::make($this->output ?? "")
            );
        }
    }

    public function timeoutHandler()
    {
        $this->process->stdin->end();
        $this->timedOut = true;
    }
}
