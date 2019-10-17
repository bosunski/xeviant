<?php

namespace App\Scripts;

use App\Notebook;
use App\Objects\PHPCode;

class RemoveComposerPackage extends Script
{
    /**
     * @var string
     */
    private $packageName;
    /**
     * @var Notebook
     */
    private $notebook;

    public function __construct(string $packageName, Notebook $notebook)
    {
        $this->notebook = $notebook;
        $this->packageName = $packageName;
    }

    public function script(): string
    {
        return  <<<CODE
# Change Directory to Notebook Directory
cd {$this->notebook->getNotebookDirectory()}
$(which composer) remove {$this->packageName}
CODE;
    }
}
