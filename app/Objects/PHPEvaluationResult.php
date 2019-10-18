<?php

namespace App\Objects;

use Illuminate\Support\Str;

class PHPEvaluationResult
{
    /**
     * @var string
     */
    private $evalResult;

    public function __construct(string $evalResult)
    {
        $this->evalResult = $evalResult;
    }

    public static function make(string $evalResult)
    {
        $evalResult = new static($evalResult);
        return $evalResult;
    }

    public function __toString()
    {
        return $this->getEvalResult();
    }

    /**
     * @return string
     */
    public function getEvalResult(): string
    {
        return str_replace(realpath(config('filesystem.codeStorage.root')), "", $this->evalResult);
    }
}
