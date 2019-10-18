<?php

namespace App\Objects;

use Illuminate\Support\Str;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;


class PHPCode
{
    /**
     * @var string
     */
    private $code;

    protected $autoloadRequires = [
        "require('vendor/autoload.php')",
        "include('vendor/autoload.php')",
        "require_once('vendor/autoload.php')",
        "include_once('vendor/autoload.php')",
    ];

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function __toString()
    {
        return $this->applyPolyFills($this->code)
                ->addAutoloader()
                ->getCode();
    }

    public function addAutoloader(): PHPCode
    {
        $this->code = Str::before($this->code,'<?php')
                    . Str::start(Str::after($this->code, "<?php"), "<?php require_once('vendor/autoload.php');");
        return $this;
    }

    public function insertStringAfter($subject): string {

    }

    public function getCode()
    {
        return $this->code;
    }

    public function applyPolyFills(string $code): PHPCode
    {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return $this;
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor(new class extends NodeVisitorAbstract {
            public function leaveNode(Node $node)
            {
                if ($node instanceof Node\Stmt\Expression
                    && $node->expr instanceof Node\Expr\FuncCall
                    && $node->expr->name instanceof Node\Name
                    && $node->expr->name->toString() === 'var_dump'
                ) {
                    $func = new Node\Expr\FuncCall(new Node\Name('cdump'), $node->expr->args, $node->expr->getAttributes());
                    return [
                        new Node\Stmt\Expression($func),
                    ];
                }
            }
        });

        $ast = $traverser->traverse($ast);
        $prettyPrinter = new Standard;
        $this->code = $prettyPrinter->prettyPrintFile($ast);

        return $this;
    }
}
