<?php

namespace PhpToZephir\Converter\Manipulator;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp;
use PhpToZephir\Converter\Dispatcher;
use PhpParser\Node\Scalar;
use PhpToZephir\Logger;

class ArrayDto
{
    private $collected = array();
    private $expr;

    /**
     * @param string $value
     */
    public function addCollected($value)
    {
        $this->collected[] = $value;
    }
    
    public function setExpr($value)
    {
        $this->expr = $value;
    }

    /**
     * @return string
     */
    public function getCollected()
    {
        if (!empty($this->collected)) {
            return implode(";\n", $this->collected).";\n";
        }

        return '';
    }
    
    public function getExpr()
    {
        return $this->expr;
    }
    
    public function hasCollected()
    {
        return !empty($this->collected);
    }
}
