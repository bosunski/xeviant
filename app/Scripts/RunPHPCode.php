<?php

namespace App\Scripts;

use App\Notebook;
use App\Objects\PHPCode;
use Throwable;

class RunPHPCode extends Script
{
    /**
     * @var PHPCode
     */
    private $PHPCode;
    /**
     * @var Notebook
     */
    private $notebook;

    public function __construct(PHPCode $PHPCode, Notebook $notebook)
    {
        $this->PHPCode = $PHPCode;
        $this->notebook = $notebook;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function script(): string
    {
        return $this->getSHScript();
    }

    public function getSHScript(): string
    {
        $script = <<<CODE
# Writes the code to file
cat > {$this->notebook->getNotebookFilePath()} << 'EOF'
$this->PHPCode
EOF

cd {$this->notebook->getNotebookDirectory()}
php -d display_errors {$this->notebook->getFileName()}
CODE;
        return $script;
    }
}
