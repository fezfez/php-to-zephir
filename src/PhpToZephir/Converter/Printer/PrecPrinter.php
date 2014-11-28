<?php

namespace PhpToZephir\Converter\Printer;

use PhpParser\Node;
use PhpToZephir\Converter\SimplePrinter;

class PrecPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pPrec";
    }

    /**
     * Prints an expression node with the least amount of parentheses necessary to preserve the meaning.
     *
     * @param Node $node                Node to pretty print
     * @param int  $parentPrecedence    Precedence of the parent operator
     * @param int  $parentAssociativity Associativity of parent operator
     *                                  (-1 is left, 0 is nonassoc, 1 is right)
     * @param int  $childPosition       Position of the node relative to the operator
     *                                  (-1 is left, 1 is right)
     *
     * @return string The pretty printed node
     */
    public function convert(Node $node, $parentPrecedence, $parentAssociativity, $childPosition)
    {
        $type = $node->getType();
        if ($this->dispatcher->issetPrecedenceMap($type) === true) {
            $childPrecedence = $this->dispatcher->getPrecedenceMap($type)[0];
            if ($childPrecedence > $parentPrecedence
                || ($parentPrecedence == $childPrecedence && $parentAssociativity != $childPosition)
            ) {
                return '('.$this->dispatcher->{'p'.$type}($node).')';
            }
        }

        return $this->dispatcher->{'p'.$type}($node);
    }
}
