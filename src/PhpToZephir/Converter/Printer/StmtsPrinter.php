<?php

namespace PhpToZephir\Converter\Printer;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class StmtsPrinter extends SimplePrinter
{
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
                    .$this->pComments($node->getAttribute('comments', array()))
                    .$this->dispatcher->p($node)
                    .($node instanceof Expr ? ';' : '');
        }

        if ($indent) {
            return preg_replace('~\n(?!$|'.Dispatcher::noIndentToken.')~', "\n    ", $result);
        } else {
            return $result;
        }
    }

    /**
     * @param array $comments
     *
     * @return string
     */
    private function pComments(array $comments)
    {
        $result = '';

        foreach ($comments as $comment) {
            $result .= $comment->getReformattedText()."\n";
        }

        return $result;
    }
}
