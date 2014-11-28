<?php

namespace PhpToZephir\Converter\Printer\Stmt;

use PhpParser\Node\Stmt;
use PhpToZephir\Converter\SimplePrinter;

class PropertyPrinter extends SimplePrinter
{
    public static function getType()
    {
        return "pStmt_Property";
    }

    public function convert(Stmt\Property $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $publicStatic    = Stmt\Class_::MODIFIER_PUBLIC + Stmt\Class_::MODIFIER_STATIC;
        $protectedStatic = Stmt\Class_::MODIFIER_PUBLIC + Stmt\Class_::MODIFIER_STATIC;
        $privateStatic   = Stmt\Class_::MODIFIER_PUBLIC + Stmt\Class_::MODIFIER_STATIC;

        if ($node->props[0]->default !== null && $node->type === $publicStatic || $node->type === $protectedStatic || $node->type === $privateStatic) {
            var_dump($node->props[0]->default);
            exit;
            $this->logger->logNode("Static default attribute not supported in zephir, (see #188). ", $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());
            $node->props[0]->default = null;
        }

        return $this->dispatcher->pModifiers($node->type).$this->dispatcher->pCommaSeparated($node->props).';';
    }
}
