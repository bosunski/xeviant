<?php

namespace App\Shell;

class FormattedShellOutput
{
    /**
     * Exit code of the Process
     *
     * @var int
     */
    private $exitCode;

    /**
     * Raw output of the Process
     *
     * @var string
     */
    private $rawOutput;

    /**
     * Flag to know if the process Timed Out
     *
     * @var bool
     */
    private $timedOut;

    public function __construct(int $exitCode, string $rawOutput, $timedOut = false)
    {
        $this->exitCode = $exitCode;
        $this->rawOutput = $rawOutput;
        $this->timedOut = $timedOut;
    }

    public function __toString()
    {
        return $this->rawOutput;
    }
}
