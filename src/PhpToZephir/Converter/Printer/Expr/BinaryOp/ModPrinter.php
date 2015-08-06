<?php

namespace PhpToZephir\Converter\Printer\Expr\BinaryOp;

use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\SimplePrinter;

class ModPrinter extends SimplePrinter
{
    public static function getType()
    {
        return 'pExpr_BinaryOp_Mod';
    }

    public function convert(BinaryOp\Mod $node)
    {
        return $this->dispatcher->pInfixOp('Expr_BinaryOp_Mod', $node->left, ' % ', $node->right);
    }
}
