<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class TryCatchPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_TryCatch";
    }

    /**
     * @param Stmt\TryCatch $node
     *
     * @return string
     */
    public function convert(Stmt\TryCatch $node)
    {
        return 'try {'.$this->dispatcher->pStmts($node->stmts)."\n".'}'
             .$this->dispatcher->pImplode($node->catches)
             .($node->finallyStmts !== null
                ? ' finally {'.$this->dispatcher->pStmts($node->finallyStmts)."\n".'}'
                : '');
    }
}
