<?php

namespace App\Objects;

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

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function __toString()
    {
        return $this->applyPolyFills($this->code);
    }

    public function applyPolyFills(string $code): string {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $ast = $parser->parse($code);
        } catch (Error $error) {
            echo "Parse error: {$error->getMessage()}\n";
            return $code;
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
        return $prettyPrinter->prettyPrintFile($ast);
    }
}
