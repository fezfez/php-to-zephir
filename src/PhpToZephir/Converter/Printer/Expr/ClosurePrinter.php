<?php

namespace PhpToZephir\Converter\Printer\Expr;

use PhpToZephir\Converter\Dispatcher;
use PhpToZephir\Logger;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;

class ClosurePrinter
{
    /**
     * @var Dispatcher
     */
    private $dispatcher = null;
    /**
     * @var Logger
     */
    private $logger = null;

    private static $converted = array();

    /**
     * @param Dispatcher $dispatcher
     * @param Logger     $logger
     */
    public function __construct(Dispatcher $dispatcher, Logger $logger)
    {
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
    }

    public static function getType()
    {
        return "pExpr_Closure";
    }

    public function convert(Expr\Closure $node)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $methodName = $this->dispatcher->getMetadata()->getClass().$this->dispatcher->getLastMethod();
        if (isset(self::$converted[$methodName])) {
            self::$converted[$methodName]++;
        } else {
            self::$converted[$methodName] = 1;
        }

        $name = $methodName."Closure".$this->N2L(count(self::$converted[$methodName]));

        $this->logger->logNode(
            sprintf('Closure does not exist in Zephir, class "%s" with __invoke is created', $name),
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
        );

        return "new ".$name.'('.$this->dispatcher->pCommaSeparated($node->uses).')';
    }

    /**
     * @param null|string $lastMethod
     * @param integer     $number
     */
    public function createClosureClass(Expr\Closure $node, $lastMethod, $number)
    {
        $this->logger->trace(__METHOD__.' '.__LINE__, $node, $this->dispatcher->getMetadata()->getFullQualifiedNameClass());

        $name = $this->dispatcher->getMetadata()->getClass().$lastMethod."Closure".$this->N2L($number);

        $this->logger->logNode(
            sprintf('Closure does not exist in Zephir, class "%s" with __invoke is created', $name),
            $node,
            $this->dispatcher->getMetadata()->getFullQualifiedNameClass()
        );

        return array(
         'name' => $name,
         'code' => $this->createClass($name, $this->dispatcher->getMetadata()->getNamespace(), $node),
        );
    }

    /**
     * @param string $name
     * @param string $namespace
     */
    private function createClass($name, $namespace, Expr\Closure $node)
    {
        $class = "namespace $namespace;

class $name
{
";

        foreach ($node->uses as $use) {
            $class .= "    private ".$use->var.";\n";
        }

        $class .= "
    public function __construct(".(!empty($node->uses) ? ''.$this->dispatcher->pCommaSeparated($node->uses) : '').")
    {
        ";
        foreach ($node->uses as $use) {
            $class .= "        let this->".$use->var." = ".$use->var.";\n";
        }
        $class .= "
    }

    public function __invoke(".$this->dispatcher->pCommaSeparated($node->params).")
    {".$this->dispatcher->pStmts($this->convertUseToMemberAttribute($node->stmts, $node->uses))."
    }
}
    ";

        return $class;
    }

    /**
     * @param Node[]            $node
     * @param Expr\ClosureUse[] $uses
     */
    private function convertUseToMemberAttribute($node, $uses)
    {
        $vars = array();
        if (is_array($node) === true) {
            $nodes = $node;
        } elseif (method_exists($node, 'getIterator') === true) {
            $nodes = $node->getIterator();
        } else {
            return $node;
        }

        foreach ($nodes as &$stmt) {
            if ($stmt instanceof Expr\Variable) {
                foreach ($uses as $use) {
                    if ($use->var === $stmt->name) {
                        $stmt->name = 'this->'.$stmt->name;
                    }
                }
            }

            $stmt = $this->convertUseToMemberAttribute($stmt, $uses);
        }

        return $node;
    }

    /**
     * @param integer $number
     */
    private function N2L($number)
    {
        $result = array();
        $tens = floor($number / 10);
        $units = $number % 10;

        $words = array(
            'units' => array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eightteen', 'Nineteen'),
            'tens' => array('', '', 'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eigthy', 'Ninety'),
        );

        if ($tens < 2) {
            $result[] = $words['units'][$tens * 10 + $units];
        } else {
            $result[] = $words['tens'][$tens];

            if ($units > 0) {
                $result[count($result) - 1] .= '-'.$words['units'][$units];
            }
        }

        if (empty($result[0])) {
            $result[0] = 'Zero';
        }

        return trim(implode(' ', $result));
    }
}
