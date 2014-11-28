<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpToZephir\Converter\SimplePrinter;

class EqualPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pExpr_BinaryOp_Equal";
    }

    public function convert(BinaryOp\Equal $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Equal', $node->left, ' == ', $node->right);
    }
}
