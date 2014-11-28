<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpParser\Node\Expr;
use PhpToZephir\Converter\SimplePrinter;

class PropertyPrinter extends SimplePrinter
{
    /**
     * @return string
     */
    public static function getType()
    {
        return "pStmt_Property";
    }

    /**
     * @param  Stmt\Property $node
     * @return string
     */
    public function convert(Stmt\Property $node)
    {
        $staticProperties = array(
            Stmt\Class_::MODIFIER_PUBLIC + Stmt\Class_::MODIFIER_STATIC,
            Stmt\Class_::MODIFIER_PROTECTED + Stmt\Class_::MODIFIER_STATIC,
            Stmt\Class_::MODIFIER_PRIVATE + Stmt\Class_::MODIFIER_STATIC,
        );

        if ($node->props[0]->default instanceof Expr\Array_ && in_array($node->type, $staticProperties) === true) {
            $node->type = $node->type - Stmt\Class_::MODIFIER_STATIC;
            $this->logger->logNode(
                "Static attribute default array not supported in zephir, (see #188). Changed into non static. ",
                $node,
                $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
            );
        }

        return $this->dispatcher->pModifiers($node->type).$this->dispatcher->pCommaSeparated($node->props).';';
    }
}
