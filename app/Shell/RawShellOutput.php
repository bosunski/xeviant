<?php

namespace App\Shell;

class RawShellOutput
{
    /**
     * @var string
     */
    public $output;

    public function __invoke($buffer)
    {
        $this->output .= $buffer;
    }

    public function __toString()
    {
        return (string) $this->output;
    }
}
