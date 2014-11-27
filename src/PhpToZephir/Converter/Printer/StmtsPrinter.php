<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use phpDocumentor\Reflection\DocBlock;
use PhpToZephir\Converter\PrettyPrinterAbstract;
use PhpToZephir\converter\Manipulator\ClassManipulator;

class StmtsPrinter
{
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;
    /**
     * @var Logger
     */
    private $logger = null;
    /**
     * @var ClassManipulator
     */
    private $classManipulator = null;

    /**
     * @param Dispatcher $dispatcher
     * @param Logger $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger, ClassManipulator $classManipulator)
    {
        $this->dispatcher       = $dispatcher;
        $this->logger           = $logger;
        $this->classManipulator = $classManipulator;
    }

    public static function getType()
    {
        return "pStmts";
    }

    /**
     * Pretty prints an array of nodes (statements) and indents them optionally.
     *
     * @param Node[] $nodes  Array of nodes
     * @param bool   $indent Whether to indent the printed nodes
     *
     * @return string Pretty printed statements
     */
    public function convert(array $nodes, $indent = true)
    {
        $result = '';
        foreach ($nodes as $node) {
            $result .= "\n"
                    . $this->pComments($node->getAttribute('comments', array()))
                    . $this->dispatcher->p($node)
                    . ($node instanceof Expr ? ';' : '');
        }

        if ($indent) {
            return preg_replace('~\n(?!$|' . Dispatcher::noIndentToken . ')~', "\n    ", $result);
        } else {
            return $result;
        }
    }

    public function pComments(array $comments)
    {
        $result = '';

        foreach ($comments as $comment) {
            $result .= $comment->getReformattedText() . "\n";
        }

        return $result;
    }

}
