<?php

namespace PhpToZephir\Converter;

use PhpToZephir\Logger;
use PhpToZephir\ClassCollector;

/**
 * @method string pExpr_Assign(\PhpParser\Node\Expr\Assign $node)
 * @method string pImplode(array $nodes, string $glue = '')
 * @method string pVarOrNewExpr(\PhpParser\Node $node)
 * @method string pCommaSeparated(array $nodes)
 * @method string pInfixOp(string $type, \PhpParser\Node $leftNode, string $operatorString, \PhpParser\Node $rightNode)
 * @method string pPrec(\PhpParser\Node $node, integer $parentPrecedence, integer $parentAssociativity, integer $childPosition)
 * @method string pExpr_Ternary(\PhpParser\Node\Expr\Ternary $node, boolean $returnAsArray = false)
 * @method string pStmts(array $nodes, boolean $indent = true)
 * @method string pModifiers(integer $modifiers)
 * @method string|array pExpr_Array(\PhpParser\Node\Expr\Array_ $node, boolean $returnAsArray = false)
 * @method string pExpr_ArrayDimFetch(\PhpParser\Node\Expr\ArrayDimFetch $node, $returnAsArray = false)
 * @method string pObjectProperty($node)
 * @method string pPrefixOp(string $type, string $operatorString, \PhpParser\Node $node)
 * @method string pEncapsList(array $encapsList, string $quote)
 * @method string pPostfixOp(string $type, \PhpParser\Node $node, string $operatorString)
 * @method string pStmt_ElseIf(\PhpParser\Node\Stmt\ElseIf_ $node)
 * @method string pStmt_If(\PhpParser\Node\Stmt\If_ $node)
 */
class Dispatcher
{
    /**
     * @var string
     */
    const noIndentToken = '_NO_INDENT_852452555255254554';
    /**
     * @var array
     */
    private $precedenceMap = array();
    /**
     * @var array
     */
    private $classes = array();
    /**
     * @var PrinterCollection
     */
    private $printerCollection = null;
    /**
     * @var Logger
     */
    private $logger = null;
    /**
     * @var string
     */
    private $lastMethod = null;
    /**
     * @var ClassMetadata
     */
    private $metadata = null;
    /**
     * @var ClassCollector
     */
    private $classCollector = null;

    /**
     * @param PrinterCollection $printerCollection
     * @param array             $precedenceMap
     */
    public function __construct(PrinterCollection $printerCollection, array $precedenceMap)
    {
        $this->printerCollection = $printerCollection;
        $this->precedenceMap = $precedenceMap;
    }

    /**
     * Pretty prints a node.
     *
     * @param \PhpParser\Node $node Node to be pretty printed
     *
     * @return string Pretty printed node
     */
    public function p()
    {
        $args = func_get_args();
        $node = $args[0];

        if (null === $node) {
            return;
        }

        $this->logger->trace('p'.$node->getType(), $node, $this->getMetadata()->getFullQualifiedNameClass());

        $class = $this->getClass('p'.$node->getType());

        return call_user_func_array(array($class, "convert"), $args);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return string
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->getClass($method), 'convert'), $arguments);
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     *
     * @return object
     */
    private function getClass($type)
    {
        if (isset($this->classes[$type]) === false) {
            if ($this->printerCollection->offsetExists($type) === false) {
                throw new \Exception(sprintf('Printer "%s" does not exist', $type));
            }
            $className = $this->printerCollection->offsetGet($type);

            $this->classes[$type] = $this->dynamicConstruct($className);
        }

        return $this->classes[$type];
    }

    /**
     * @param string $value
     */
    public function setLastMethod($value)
    {
        $this->lastMethod = $value;
    }

    /**
     * @return string
     */
    public function getLastMethod()
    {
        return $this->lastMethod;
    }

    /**
     * @param string $className
     *
     * @return object
     */
    private function dynamicConstruct($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if ($reflectionClass->getConstructor() === null) {
            return new $className();
        }
        $dependencies = array();

        foreach ($reflectionClass->getConstructor()->getParameters() as $nmb => $param) {
            $name = $param->getClass()->name;

            if ($name === 'PhpToZephir\Converter\Dispatcher') {
                $dependencies[] = $this;
            } elseif ($name === 'PhpToZephir\Logger') {
                $dependencies[] = $this->logger;
            } elseif ($name === 'PhpToZephir\ClassCollector') {
                $dependencies[] = $this->classCollector;
            } else {
                $dependencies[] = $this->dynamicConstruct($name);
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getPrecedenceMap($type)
    {
        return $this->precedenceMap[$type];
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function issetPrecedenceMap($type)
    {
        return isset($this->precedenceMap[$type]);
    }

    /**
     * Pretty prints an array of statements.
     *
     * @param \PhpParser\Node[] $stmts Array of statements
     *
     * @return string Pretty printed statements
     */
    public function convert(array $stmts, ClassMetadata $metadata, ClassCollector $classCollector, Logger $logger)
    {
        $this->metadata = $metadata;
        $this->classCollector = $classCollector;
        $this->logger = $logger;

        return ltrim(str_replace("\n".self::noIndentToken, "\n", $this->pStmts($stmts, false)));
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
