<?php

namespace PhpToZephir\Converter;

use PhpToZephir\Logger;

/**
 * @method string pExpr_Assign()
 * @method string pImplode()
 * @method string pVarOrNewExpr()
 * @method string pCommaSeparated()
 * @method string pInfixOp()
 * @method string pPrec()
 * @method string pExpr_Ternary()
 * @method string pStmts()
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
     * @param PrinterCollection $printerCollection
     * @param Logger $logger
     * @param array $precedenceMap
     */
    public function __construct(PrinterCollection $printerCollection, Logger $logger, array $precedenceMap)
    {
        $this->printerCollection = $printerCollection;
        $this->logger = $logger;
        $this->precedenceMap = $precedenceMap;
    }

    /**
     * Pretty prints a node.
     *
     * @param Node $node Node to be pretty printed
     *
     * @return string Pretty printed node
     */
    public function p($node)
    {
        if(null === $node) {
            return;
        }

        return $this->getClass('p' . $node->getType())->convert($node);
    }

    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->getClass($method), 'convert'), $arguments);
    }

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

    public function getLastMethod()
    {
        return $this->lastMethod;
    }

    private function dynamicConstruct($className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if ($reflectionClass->getConstructor() === null) {
            return new $className;
        }
        $dependencies = array();

        foreach ($reflectionClass->getConstructor()->getParameters() as $nmb => $param) {
            $name = $param->getClass()->name;

            if ($name === 'PhpToZephir\Converter\Dispatcher') {
                $dependencies[] = $this;
            } elseif ($name === 'PhpToZephir\Logger') {
                $dependencies[] = $this->logger;
            } else {
                $dependencies[] = $this->dynamicConstruct($name);
            }
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    /**
     * @param string $type
     * @return array
     */
    public function getPrecedenceMap($type)
    {
        return $this->precedenceMap[$type];
    }

    /**
     * @param string $type
     * @return boolean
     */
    public function issetPrecedenceMap($type)
    {
        return isset($this->precedenceMap[$type]);
    }

    /**
     * Pretty prints an array of statements.
     *
     * @param Node[] $stmts Array of statements
     *
     * @return string Pretty printed statements
     */
    public function convert(array $stmts, ClassMetadata $metadata)
    {
        $this->metadata = $metadata;
        return ltrim(str_replace("\n" . self::noIndentToken, "\n", $this->pStmts($stmts, false)));
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
