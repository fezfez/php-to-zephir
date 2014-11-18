<?php

namespace PhpToZephir;

use PhpParser\PrettyPrinterAbstract;
use PhpParser\Node;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\MagicConst;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Stmt;
use PhpParser\Node\Name;
use phpDocumentor\Reflection\DocBlock;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Scalar\String;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;


class Converter extends PrettyPrinterAbstract
{
    private $use = array();
    private $actualNamespace = null;
    private $classes = array();
    private $class = null;
    private $fullClass = null;
    private $fileName = null;
    private $additionalClass = array();
    private $lastMethodConverted = null;
    private $classesAlias = array();
    private $classCollected = array();
    /**
     * @var TypeFinder
     */
    private $typeFinder = null;
    /**
     * @var Logger
     */
    private $logger = null;

    public function __construct(TypeFinder $typeFinder, Logger $logger)
    {
        $this->typeFinder = $typeFinder;
        $this->logger     = $logger;
        parent::__construct();
    }

    public function prettyPrint(array $stmts, $fileName = null, array $classes = array())
    {
        $this->fileName = $fileName;
        $this->classCollected = $classes;

        return array(
            'code' => parent::prettyPrint($stmts),
            'namespace' => $this->actualNamespace,
            'class'     => $this->class,
            'additionalClass' => $this->additionalClass
        );
    }

    // Special nodes

    public function pParam(Node\Param $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return ($node->type ? (is_string($node->type) ? $node->type : $this->p($node->type)) . ' ' : '')
             . ($node->byRef ? '&' : '')
             . ($node->variadic ? '... ' : '')
             . '' . $node->name
             . ($node->default ? ' = ' . $this->p($node->default) : '') . '';
    }

    public function pArg(Node\Arg $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return ($node->byRef ? '&' : '') . ($node->unpack ? '...' : '') . $this->p($node->value);
    }

    public function pConst(Node\Const_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $node->name . ' = ' . $this->p($node->value);
    }

    // Names

    public function pName(Name $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return $this->findRightClass($node);
    }

    public function pName_FullyQualified(Name\FullyQualified $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return '\\' . implode('\\', $node->parts);
    }

    public function pName_Relative(Name\Relative $node) {
        return 'namespace\\' . implode('\\', $node->parts);
    }

    // Magic Constants

    public function pScalar_MagicConst_Class(MagicConst\Class_ $node) {
        return '__CLASS__';
    }

    public function pScalar_MagicConst_Dir(MagicConst\Dir $node) {
        return '__DIR__';
    }

    public function pScalar_MagicConst_File(MagicConst\File $node) {
        return '__FILE__';
    }

    public function pScalar_MagicConst_Function(MagicConst\Function_ $node) {
        return '__FUNCTION__';
    }

    public function pScalar_MagicConst_Line(MagicConst\Line $node) {
        return '__LINE__';
    }

    public function pScalar_MagicConst_Method(MagicConst\Method $node) {
        return '__METHOD__';
    }

    public function pScalar_MagicConst_Namespace(MagicConst\Namespace_ $node) {
        return '__NAMESPACE__';
    }

    public function pScalar_MagicConst_Trait(MagicConst\Trait_ $node) {
        return '__TRAIT__';
    }

    // Scalars

    public function pScalar_String(Scalar\String $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return '"' . $this->pNoIndent(addcslashes($node->value, '\"\\')) . '"';
    }

    public function pScalar_Encapsed(Scalar\Encapsed $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return '"' . $this->pEncapsList($node->parts, '"') . '"';
    }

    public function pScalar_LNumber(Scalar\LNumber $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $node->value;
    }

    public function pScalar_DNumber(Scalar\DNumber $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $node->value;
    }

    private function convertListStmtToAssign($node)
    {
        $type = 'Expr_Assign';
        $leftNode = $node->var;
        $operatorString = ' = ';
        $rightNode = $node->expr;

        list($precedence, $associativity) = $this->precedenceMap[$type];

        $vars = array();
        foreach ($node->var->vars as $count => $var) {
            if (null === $var) {
                $pList[] = '';
            } else {
                $vars[] = $this->p($var);
                $pList[] = 'let ' . $this->p($var) . ' = ' . $this->pPrec($rightNode, $precedence, $associativity, 1) . '[' . $count . '];';
            }
        }

        return 'var ' . implode(", ", $vars) . ";\n" . implode("\n", $pList);
    }

    // Assignments

    public function pExpr_Assign(Expr\Assign $node) {
        $type = 'Expr_Assign';
        $leftNode = $node->var;
        $operatorString = ' = ';
        $rightNode = $node->expr;

        list($precedence, $associativity) = $this->precedenceMap[$type];

        if ($node->var instanceof Expr\List_) {
            return $this->convertListStmtToAssign($node);
        } elseif ($leftNode instanceof Expr\ArrayDimFetch || $rightNode instanceof Expr\ArrayDimFetch) {

            $head = '';

            if ($rightNode instanceof ArrayDimFetch) {
                if (false === $splitedArray = $this->arrayNeedToBeSplit($rightNode)) {
                    $rightString = $this->pPrec($rightNode, $precedence, $associativity, 1);
                } else {
                    $result = $this->pExpr_ArrayDimFetch($rightNode, true);
                    $head .= $result['head'];
                    $rightString = $result['lastExpr'];
                }
            } elseif ($rightNode instanceof Variable ||
                $rightNode instanceof Scalar ||
                $rightNode instanceof Array_ ||
                $rightNode instanceof Expr\StaticCall ||
                $rightNode instanceof Expr\FuncCall ||
                $rightNode instanceof Expr\ConstFetch ||
                $rightNode instanceof Expr\Clone_ ||
                $rightNode instanceof Expr\New_ ||
                $rightNode instanceof Expr\ClassConstFetch
            ) {
                $rightString = $this->pPrec($rightNode, $precedence, $associativity, 1);
            } else {
                $head .= $this->pPrec($rightNode, $precedence, $associativity, 1) . ";\n";
                $rightString = $this->p($rightNode->var);
            }

            return $head . 'let ' . $this->pPrec($leftNode, $precedence, $associativity, -1)
                . $operatorString
                . $rightString;

        } elseif($rightNode instanceof Expr\Assign) { // multiple assign
            $valueToAssign = ' = ' . $this->p($this->findValueToAssign($rightNode));
            $vars = array($this->pPrec($leftNode, $precedence, $associativity, -1));
            foreach($this->findVarToAssign($rightNode) as $nodeAssigned) {
                $vars[] = $nodeAssigned;
            }

            $toReturn = '';

            foreach ($vars as $var) {
                $toReturn .= 'let ' . $var . $valueToAssign . ";\n";
            }

            return $toReturn;
        } elseif ($rightNode instanceof Variable || $rightNode instanceof Scalar || $rightNode instanceof Array_) {
            $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
            return 'let ' . $this->pPrec($leftNode, $precedence, $associativity, -1)
            . $operatorString
            . $this->pPrec($rightNode, $precedence, $associativity, 1);
        } else {
            $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

            return 'let ' . $this->pPrec($leftNode, $precedence, $associativity, -1)
                   . $operatorString . ' ' . $this->p($rightNode);
        }
    }

    private function findValueToAssign($rightNode)
    {
        if($rightNode->expr instanceof Expr\Assign) {
            return $this->findValueToAssign($rightNode->expr);
        } else {
            return $rightNode->expr;
        }
    }

    private function findVarToAssign($rightNode, array $toAssign = array())
    {
        if($rightNode->expr instanceof Expr\Assign) {
            $toAssign = $this->findVarToAssign($rightNode->expr);
        }

        $toAssign[] = $this->p($rightNode->var);

        return $toAssign;
    }

    public function pExpr_AssignRef(Expr\AssignRef $node) {
        $this->logger->logNode('(=&) AssignRef does not exist in zephir, assign', $node, $this->fullClass);
        return 'let ' . $this->pInfixOp('Expr_AssignRef', $node->var, ' = ', $node->expr);
    }

    public function pExpr_AssignOp_Plus(AssignOp\Plus $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Plus', $node->var, ' += ', $node->expr);
    }

    public function pExpr_AssignOp_Minus(AssignOp\Minus $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Minus', $node->var, ' -= ', $node->expr);
    }

    public function pExpr_AssignOp_Mul(AssignOp\Mul $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Mul', $node->var, ' *= ', $node->expr);
    }

    public function pExpr_AssignOp_Div(AssignOp\Div $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Div', $node->var, ' /= ', $node->expr);
    }

    public function pExpr_AssignOp_Concat(AssignOp\Concat $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Concat', $node->var, ' .= ', $node->expr);
    }

    public function pExpr_AssignOp_Mod(AssignOp\Mod $node) {
        return 'let ' . $this->pInfixOp('Expr_AssignOp_Mod', $node->var, ' %= ', $node->expr);
    }

    public function pExpr_AssignOp_BitwiseAnd(AssignOp\BitwiseAnd $node) {
        $this->logger->logNode('(&=) BitwiseAnd does not exist in zephir, assign', $node, $this->fullClass);
        return 'let ' . $this->pInfixOp('Expr_AssignOp_BitwiseAnd', $node->var, ' = ', $node->expr);
    }

    public function pExpr_AssignOp_BitwiseOr(AssignOp\BitwiseOr $node) {
        return $this->pInfixOp('Expr_AssignOp_BitwiseOr', $node->var, ' |= ', $node->expr);
    }

    public function pExpr_AssignOp_BitwiseXor(AssignOp\BitwiseXor $node) {
        return $this->pInfixOp('Expr_AssignOp_BitwiseXor', $node->var, ' ^= ', $node->expr);
    }

    public function pExpr_AssignOp_ShiftLeft(AssignOp\ShiftLeft $node) {
        return $this->pInfixOp('Expr_AssignOp_ShiftLeft', $node->var, ' <<= ', $node->expr);
    }

    public function pExpr_AssignOp_ShiftRight(AssignOp\ShiftRight $node) {
        return $this->pInfixOp('Expr_AssignOp_ShiftRight', $node->var, ' >>= ', $node->expr);
    }

    public function pExpr_AssignOp_Pow(AssignOp\Pow $node) {
        return $this->pInfixOp('Expr_AssignOp_Pow', $node->var, ' **= ', $node->expr);
    }

    // Binary expressions

    public function pExpr_BinaryOp_Plus(BinaryOp\Plus $node) {
        return $this->pInfixOp('Expr_BinaryOp_Plus', $node->left, ' + ', $node->right);
    }

    public function pExpr_BinaryOp_Minus(BinaryOp\Minus $node) {
        return $this->pInfixOp('Expr_BinaryOp_Minus', $node->left, ' - ', $node->right);
    }

    public function pExpr_BinaryOp_Mul(BinaryOp\Mul $node) {
        return $this->pInfixOp('Expr_BinaryOp_Mul', $node->left, ' * ', $node->right);
    }

    public function pExpr_BinaryOp_Div(BinaryOp\Div $node) {
        return $this->pInfixOp('Expr_BinaryOp_Div', $node->left, ' / ', $node->right);
    }

    public function pExpr_BinaryOp_Concat(BinaryOp\Concat $node) {
        return $this->pInfixOp('Expr_BinaryOp_Concat', $node->left, ' . ', $node->right);
    }

    public function pExpr_BinaryOp_Mod(BinaryOp\Mod $node) {
        return $this->pInfixOp('Expr_BinaryOp_Mod', $node->left, ' % ', $node->right);
    }

    public function pExpr_BinaryOp_BooleanAnd(BinaryOp\BooleanAnd $node) {
        return $this->pInfixOp('Expr_BinaryOp_BooleanAnd', $node->left, ' && ', $node->right);
    }

    public function pExpr_BinaryOp_BooleanOr(BinaryOp\BooleanOr $node) {
        return $this->pInfixOp('Expr_BinaryOp_BooleanOr', $node->left, ' || ', $node->right);
    }

    public function pExpr_BinaryOp_BitwiseAnd(BinaryOp\BitwiseAnd $node) {
        return $this->pInfixOp('Expr_BinaryOp_BitwiseAnd', $node->left, ' & ', $node->right);
    }

    public function pExpr_BinaryOp_BitwiseOr(BinaryOp\BitwiseOr $node) {
        return $this->pInfixOp('Expr_BinaryOp_BitwiseOr', $node->left, ' | ', $node->right);
    }

    public function pExpr_BinaryOp_BitwiseXor(BinaryOp\BitwiseXor $node) {
        return $this->pInfixOp('Expr_BinaryOp_BitwiseXor', $node->left, ' ^ ', $node->right);
    }

    public function pExpr_BinaryOp_ShiftLeft(BinaryOp\ShiftLeft $node) {
        return $this->pInfixOp('Expr_BinaryOp_ShiftLeft', $node->left, ' << ', $node->right);
    }

    public function pExpr_BinaryOp_ShiftRight(BinaryOp\ShiftRight $node) {
        return $this->pInfixOp('Expr_BinaryOp_ShiftRight', $node->left, ' >> ', $node->right);
    }

    public function pExpr_BinaryOp_Pow(BinaryOp\Pow $node) {
        return $this->pInfixOp('Expr_BinaryOp_Pow', $node->left, ' ** ', $node->right);
    }

    public function pExpr_BinaryOp_LogicalAnd(BinaryOp\LogicalAnd $node) {
        return $this->pInfixOp('Expr_BinaryOp_LogicalAnd', $node->left, ' and ', $node->right);
    }

    public function pExpr_BinaryOp_LogicalOr(BinaryOp\LogicalOr $node) {
        return $this->pInfixOp('Expr_BinaryOp_LogicalOr', $node->left, ' or ', $node->right);
    }

    public function pExpr_BinaryOp_LogicalXor(BinaryOp\LogicalXor $node) {
        return $this->pInfixOp('Expr_BinaryOp_LogicalXor', $node->left, ' xor ', $node->right);
    }

    public function pExpr_BinaryOp_Equal(BinaryOp\Equal $node) {
        return $this->pInfixOp('Expr_BinaryOp_Equal', $node->left, ' == ', $node->right);
    }

    public function pExpr_BinaryOp_NotEqual(BinaryOp\NotEqual $node) {
        return $this->pInfixOp('Expr_BinaryOp_NotEqual', $node->left, ' != ', $node->right);
    }

    public function pExpr_BinaryOp_Identical(BinaryOp\Identical $node) {
        return $this->pInfixOp('Expr_BinaryOp_Identical', $node->left, ' === ', $node->right);
    }

    public function pExpr_BinaryOp_NotIdentical(BinaryOp\NotIdentical $node) {
        return $this->pInfixOp('Expr_BinaryOp_NotIdentical', $node->left, ' !== ', $node->right);
    }

    public function pExpr_BinaryOp_Greater(BinaryOp\Greater $node) {
        return $this->pInfixOp('Expr_BinaryOp_Greater', $node->left, ' > ', $node->right);
    }

    public function pExpr_BinaryOp_GreaterOrEqual(BinaryOp\GreaterOrEqual $node) {
        return $this->pInfixOp('Expr_BinaryOp_GreaterOrEqual', $node->left, ' >= ', $node->right);
    }

    public function pExpr_BinaryOp_Smaller(BinaryOp\Smaller $node) {
        return $this->pInfixOp('Expr_BinaryOp_Smaller', $node->left, ' < ', $node->right);
    }

    public function pExpr_BinaryOp_SmallerOrEqual(BinaryOp\SmallerOrEqual $node) {
        return $this->pInfixOp('Expr_BinaryOp_SmallerOrEqual', $node->left, ' <= ', $node->right);
    }

    public function pExpr_Instanceof(Expr\Instanceof_ $node) {
        return $this->pInfixOp('Expr_Instanceof', $node->expr, ' instanceof ', $node->class);
    }

    // Unary expressions

    public function pExpr_BooleanNot(Expr\BooleanNot $node) {
        return $this->pPrefixOp('Expr_BooleanNot', '!', $node->expr);
    }

    public function pExpr_BitwiseNot(Expr\BitwiseNot $node) {
        return $this->pPrefixOp('Expr_BitwiseNot', '~', $node->expr);
    }

    public function pExpr_UnaryMinus(Expr\UnaryMinus $node) {
        return $this->pPrefixOp('Expr_UnaryMinus', '-', $node->expr);
    }

    public function pExpr_UnaryPlus(Expr\UnaryPlus $node) {
        return $this->pPrefixOp('Expr_UnaryPlus', '+', $node->expr);
    }

    public function pExpr_PreInc(Expr\PreInc $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return 'let ' .$this->pPostfixOp('Expr_PostInc', $node->var, '++');
    }

    public function pExpr_PreDec(Expr\PreDec $node) {
        return 'let ' . $this->pPostfixOp('Expr_PreDec', $node->var, '--');
    }

    public function pExpr_PostInc(Expr\PostInc $node) {
        return 'let ' . $this->pPostfixOp('Expr_PostInc', $node->var, '++');
    }

    public function pExpr_PostDec(Expr\PostDec $node) {
        return 'let ' . $this->pPostfixOp('Expr_PostDec', $node->var, '--');
    }

    public function pExpr_ErrorSuppress(Expr\ErrorSuppress $node) {
        return $this->pPrefixOp('Expr_ErrorSuppress', '@', $node->expr);
    }

    // Casts

    public function pExpr_Cast_Int(Cast\Int $node) {
        return $this->pPrefixOp('Expr_Cast_Int', '(int) ', $node->expr);
    }

    public function pExpr_Cast_Double(Cast\Double $node) {
        return $this->pPrefixOp('Expr_Cast_Double', '(double) ', $node->expr);
    }

    public function pExpr_Cast_String(Cast\String $node) {
        return $this->pPrefixOp('Expr_Cast_String', '(string) ', $node->expr);
    }

    public function pExpr_Cast_Array(Cast\Array_ $node) {
        return $this->pPrefixOp('Expr_Cast_Array', '(array) ', $node->expr);
    }

    public function pExpr_Cast_Object(Cast\Object $node) {
        return $this->pPrefixOp('Expr_Cast_Object', '(object) ', $node->expr);
    }

    public function pExpr_Cast_Bool(Cast\Bool $node) {
        return $this->pPrefixOp('Expr_Cast_Bool', '(bool) ', $node->expr);
    }

    public function pExpr_Cast_Unset(Cast\Unset_ $node) {
        $this->logger->logNode('(unset) does not exist in zephir, remove cast', $node, $this->fullClass);
        return $this->pPrefixOp('Expr_Cast_Unset', '', $node->expr);
    }

    // Function calls and similar constructs

    public function pExpr_FuncCall(Expr\FuncCall $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        if($node->name instanceof Expr\Variable) {
            return '{' . $this->p($node->name) . '}(' . $this->pCommaSeparated($node->args) . ')';
        } else {
            return $this->p($node->name) . '(' . $this->pCommaSeparated($node->args) . ')';
        }
    }

    public function pExpr_MethodCall(Expr\MethodCall $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return $this->pVarOrNewExpr($node->var) . '->' . $this->pObjectProperty($node->name)
             . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_StaticCall(Expr\StaticCall $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return (($node->class instanceof Expr\Variable) ? '{' . $this->p($node->class) . '}' : $this->p($node->class)) . '::'
             . ($node->name instanceof Expr
                ? ($node->name instanceof Expr\Variable
                   || $node->name instanceof Expr\ArrayDimFetch
                   ? $this->p($node->name)
                   : '{' . $this->p($node->name) . '}')
                : $node->name)
             . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_Empty(Expr\Empty_ $node) {
        return 'empty(' . $this->p($node->expr) . ')';
    }

    public function pExpr_Isset(Expr\Isset_ $node)
    {
        return 'isset ' . $this->pCommaSeparated($node->vars) . '';
    }

    public function pExpr_Print(Expr\Print_ $node) {
        return 'print ' . $this->p($node->expr);
    }

    public function pExpr_Eval(Expr\Eval_ $node)
    {
        return 'eval(' . $this->p($node->expr) . ')';
    }

    public function pExpr_Include(Expr\Include_ $node)
    {
        static $map = array(
            Expr\Include_::TYPE_INCLUDE      => 'include',
            Expr\Include_::TYPE_INCLUDE_ONCE => 'include_once',
            Expr\Include_::TYPE_REQUIRE      => 'require',
            Expr\Include_::TYPE_REQUIRE_ONCE => 'require_once',
        );

        return $map[$node->type] . ' ' . $this->p($node->expr);
    }

    // Other

    public function pExpr_Variable(Expr\Variable $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        if ($node->name instanceof Expr) {
            return '{' . $this->p($node->name) . '}';
        } else {
            return '' . $this->replaceReservedWords($node->name);
        }
    }

    public function pExpr_Array(Expr\Array_ $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return '[' . $this->pCommaSeparated($node->items) . ']';
    }

    public function pExpr_ArrayItem(Expr\ArrayItem $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return (null !== $node->key ? $this->p($node->key) . ' : ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->value);
    }

    private function isInvalidInArrayDimFetch($node)
    {
        if ($node->dim instanceof BinaryOp\Concat) {
            return $this->isInvalidInArrayDimFetch($node->dim->left)
                && $this->isInvalidInArrayDimFetch($node->dim->right);
        } else {
            return $this->isInvalidIn($node->dim);
        }
    }

    private function isInvalidIn($node)
    {
        return ($node instanceof Expr\Variable) === false
            && ($node instanceof Expr\ClassConstFetch) === false
            && ($node instanceof Expr\ConstFetch) === false
            && ($node instanceof BinaryOp\Minus) === false
            && ($node instanceof Scalar) === false
            && $node !== null;
    }

    private function findComplexArrayDimFetch($node, $collected = array())
    {
        if ($this->isInvalidInArrayDimFetch($node) === true) {
            if ($node->dim instanceof Expr\FuncCall) {
                $this->logger->trace(__METHOD__ . ' ' . __LINE__ . ' Non supported funccall in array', $node, $this->fullClass);
            } else {
                $collected[] = array(
                    'expr' => $this->p($node->dim) . ";\n",
                    'splitTab' => true,
                    'var' => $this->p($node->dim->var)
                );
            }
        } else {
            if ($node->dim === null) {
                $collected[] = array('expr' => $this->p($node->var), 'splitTab' => false);
            } else {
                $collected[] = array('expr' => $this->p($node->dim), 'splitTab' => false);
            }
        }

        if ($node->var instanceof Expr\ArrayDimFetch) {
            $collected = $this->findComplexArrayDimFetch($node->var, $collected);
        } else {
            $collected[] = $node->var;
        }

        return $collected;
    }

    private function arrayNeedToBeSplit(Expr\ArrayDimFetch $node)
    {
        $collected = array_reverse($this->findComplexArrayDimFetch($node));

        foreach ($collected as $rst) {
            if (is_array($rst) && $rst['splitTab'] === true) {
                return $collected;
            }
        }

        return false;
    }

    public function pExpr_ArrayDimFetch(Expr\ArrayDimFetch $node, $returnAsArray = false) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        $collected = $this->arrayNeedToBeSplit($node);

        if($collected !== false) {

            $var = $collected[0];
            unset($collected[0]);
            $lastExpr = null;


            $head = "var tmpArray;\n";
            $lastSplitTable = true;
            foreach ($collected as $expr) {
                if ($expr['splitTab'] === true) {
                    $head .= $expr['expr'];
                    if ($expr !== end($collected)) {
                        $head .= 'let tmpArray = ';
                        $head .= $this->p($var) . '[' . $expr['var'] . ']';
                    } else {
                        $lastExpr = $this->p($var) . '[' . $expr['var'] . ']';
                    }

                    $lastSplitTable = true;
                } else {
                    if ($lastSplitTable === true) {
                        if ($expr !== end($collected)) {
                            $head .= 'let tmpArray = ';
                            $head .= $this->p($var) . '[' . $expr['expr'] . ']';
                        } else {
                            $lastExpr = $this->p($var) . '[' . $expr['expr'] . ']';
                        }
                    }
                }

                if ($expr !== end($collected)) {
                    $head .= ';' . "\n";
                }
            }

            if ($returnAsArray === true) {
                return array(
                    'head' => $head,
                    'lastExpr' => $lastExpr
                );
            } else {
                return $head;
            }
        } else {
            $result = $this->pVarOrNewExpr($node->var)
                 . '[' . (null !== $node->dim ? $this->p($node->dim) : '') . ']';

            if ($returnAsArray === true) {
                return array(
                    'head' => '',
                    'lastExpr' => $result
                );
            } else {
                return $result;
            }
        }
    }

    public function pExpr_ConstFetch(Expr\ConstFetch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return implode('\\', $node->name->parts);
    }

    public function pExpr_ClassConstFetch(Expr\ClassConstFetch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $this->p($node->class) . '::' . $node->name;
    }

    public function pExpr_PropertyFetch(Expr\PropertyFetch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $this->pVarOrNewExpr($node->var) . '->' . $this->pObjectProperty($node->name);
    }

    public function pExpr_StaticPropertyFetch(Expr\StaticPropertyFetch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $this->p($node->class) . '::$' . $this->pObjectProperty($node->name);
    }

    public function pExpr_ShellExec(Expr\ShellExec $node) {
        return '`' . $this->pEncapsList($node->parts, '`') . '`';
    }

    public function pExpr_Closure(Expr\Closure $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        $name = $this->class . $this->lastMethodConverted . "Closure" . $this->N2L(count($this->additionalClass));

        $this->logger->logNode(
            sprintf('Closure does not exist in Zephir, class "%s" with __invoke is created', $name),
            $node,
            $this->fullClass
        );

        $this->additionalClass[] = array(
            'name' => $name,
            'code' => $this->createClass($name, $this->actualNamespace, $node)
        );

        return "new " . $name . '(' . $this->pCommaSeparated($node->uses) . ')';
    }

    private function N2L($number)
    {
    	$result = array();
    	$tens = floor($number / 10);
    	$units = $number % 10;

    	$words = array
    	(
    		'units' => array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eightteen', 'Nineteen'),
    		'tens' => array('', '', 'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eigthy', 'Ninety')
    	);

    	if ($tens < 2)
    	{
    		$result[] = $words['units'][$tens * 10 + $units];
    	}

    	else
    	{
    		$result[] = $words['tens'][$tens];

    		if ($units > 0)
    		{
    			$result[count($result) - 1] .= '-' . $words['units'][$units];
    		}
    	}

    	if (empty($result[0]))
    	{
    		$result[0] = 'Zero';
    	}

    	return trim(implode(' ', $result));
    }

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
                        $stmt->name = 'this->' . $stmt->name;
                    }
                }
            }

            $stmt = $this->convertUseToMemberAttribute($stmt, $uses);
        }

        return $node;
    }

    private function createClass($name, $namespace, Expr\Closure $node)
    {
        $class = "namespace $namespace;

class $name
{
 ";

        foreach ($node->uses as $use) {
            $class .= "    private " . $use->var . ";\n";
        }

$class .= "
    public function __construct("  . (!empty($node->uses) ? '' . $this->pCommaSeparated($node->uses) : '') . ")
    {
";
        foreach ($node->uses as $use) {
            $class .= "        let this->" . $use->var . " = " . $use->var . ";\n";
        }
$class .= "
    }

    public function __invoke(" . $this->pCommaSeparated($node->params) . ")
    {" . $this->pStmts($this->convertUseToMemberAttribute($node->stmts, $node->uses)) . "
    }
}
";

        return $class;
    }

    public function pExpr_ClosureUse(Expr\ClosureUse $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        if ($node->byRef) {
            $this->logger->logNode("Zephir not support reference parameters for now. Stay tuned for https://github.com/phalcon/zephir/issues/203", $node, $this->class);
        }

        return $node->var;
    }

    private function replaceReservedWords($string)
    {
        $string = str_replace('inline', 'inlinee', $string);
        $string = str_replace('Inline', 'Inlinee', $string);
        $string = str_replace('array', 'myArray', $string);

        if (ctype_upper($string)) {
            $string = strtolower($string);
        }

        return $string;
    }

    private function findRightClass(Node\Name $node)
    {
        $class = implode('\\', $node->parts);
        $lastPartsClass = array_map(function ($value) { return substr(strrchr($value, '\\'), 1); }, $this->classes);

        $class = $this->replaceReservedWords($class);

        if (in_array($class, $this->classes)) {
            return '\\' . $class;
        } elseif (array_key_exists($class, $this->classesAlias)) {
            $classKey = array_keys($this->classesAlias, $class);
            return '\\' . $this->classesAlias[$class];
        } elseif (false !== $key = array_search($class, $lastPartsClass)) {
            return '\\' . $this->classes[$key];
        } elseif (false !== $key = array_search($this->actualNamespace . '\\' . $class, $this->classCollected)) {
            return '\\' . $this->classCollected[$key];
        } else {
            return $class;
        }
    }

    public function pExpr_New(Expr\New_ $node) {
        return 'new ' . $this->p($node->class) . '(' . $this->pCommaSeparated($node->args) . ')';
    }

    public function pExpr_Clone(Expr\Clone_ $node) {
        return 'clone ' . $this->p($node->expr);
    }

    public function pExpr_Ternary(Expr\Ternary $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        // a bit of cheating: we treat the ternary as a binary op where the ?...: part is the operator.
        // this is okay because the part between ? and : never needs parentheses.
        return $this->pInfixOp('Expr_Ternary',
            $node->cond, ' ?' . (null !== $node->if ? ' ' . $this->p($node->if) . ' ' : '') . ': ', $node->else
        );
    }

    public function pExpr_Exit(Expr\Exit_ $node) {
        return 'die' . (null !== $node->expr ? '(' . $this->p($node->expr) . ')' : '');
    }

    public function pExpr_Yield(Expr\Yield_ $node) {
        $this->logger->logNode('Yield does not exist in zephir', $node, $this->fullClass);

        if ($node->value === null) {
            return 'yield';
        } else {
            // this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
            return '(yield '
                 . ($node->key !== null ? $this->p($node->key) . ' => ' : '')
                 . $this->p($node->value)
                 . ')';
        }
    }

    // Declarations

    public function pStmt_Namespace(Stmt\Namespace_ $node) {
        $this->actualNamespace =  implode('\\', $node->name->parts);
        if ($this->canUseSemicolonNamespaces) {
            return 'namespace ' . $this->actualNamespace . ';' . "\n" . $this->pStmts($node->stmts, false);
        } else {
            return 'namespace' . (null !== $node->name ? ' ' . $this->p($node->name) : '')
                 . ' {' . $this->pStmts($node->stmts) . "\n" . '}';
        }
    }

    public function pStmt_Use(Stmt\Use_ $node) {
        foreach ($node->uses as $use) {
            $this->pStmt_UseUse($use);
        }

        $this->use = array_merge($this->use, $node->uses);
        return;
    }

    public function pStmt_UseUse(Stmt\UseUse $node) {
        $this->classes[] = $this->replaceReservedWords(implode('\\', $node->name->parts));
        if ($node->name->getLast() !== $node->alias) {
            $this->classesAlias[$node->alias] = $this->replaceReservedWords(implode('\\', $node->name->parts));
        }
        return ''; $this->p($node->name)
             . ($node->name->getLast() !== $node->alias ? ' as ' . $node->alias : '');
    }

    public function pStmt_Interface(Stmt\Interface_ $node) {
        $node->name = $this->replaceReservedWords($node->name);
        $this->classes[] = $this->actualNamespace . '\\' . $node->name;
        $this->class = $node->name;

        $extendsStmt = '';

        if (!empty($node->extends)) {
            $extendsStmt = ' extends ';
            $extends = array();
            foreach ($node->extends as $extend) {
                $extends[] = $this->findRightClass($extend);
            }

            $extendsStmt .= implode(', ', $extends);
        }

        return 'interface ' . $node->name
             . $extendsStmt
             . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Class(Stmt\Class_ $node) {
        $node->name = $this->replaceReservedWords($node->name);
        $this->classes[] = $this->actualNamespace . '\\' . $node->name;
        $this->class = $node->name;
        $this->fullClass = $this->actualNamespace . '\\' . $node->name;
        return $this->pModifiers($node->type)
             . 'class ' . $node->name
             . (null !== $node->extends ? ' extends ' . $this->findRightClass($node->extends) : '')
             . (!empty($node->implements) ? ' implements ' . $this->p_implements($node->implements) : '')
             . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}';
    }

    private function p_implements($nodes)
    {
        $classes = array();

        foreach ($nodes as $node) {
            $classes[] = $this->findRightClass($node);
        }

        return implode(', ', $classes);
    }

    public function pStmt_Trait(Stmt\Trait_ $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->fullClass);
    }

    public function pStmt_TraitUse(Stmt\TraitUse $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->fullClass);
    }

    public function pStmt_TraitUseAdaptation_Precedence(Stmt\TraitUseAdaptation\Precedence $node) {
        return $this->p($node->trait) . '::' . $node->method
             . ' insteadof ' . $this->pCommaSeparated($node->insteadof) . ';';
    }

    public function pStmt_TraitUseAdaptation_Alias(Stmt\TraitUseAdaptation\Alias $node)
    {
        $this->logger->logNode('trait does not exist in zephir', $node, $this->fullClass);
    }

    public function pStmt_Property(Stmt\Property $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        if ($node->props[0]->default !== null && $node->type === 9 || $node->type === 10 || $node->type === 12 ) {
            $this->logger->logNode("Static default attribute not supported in zephir, (see #188). ", $node, $this->fullClass);
            $node->props[0]->default = null;
        }
        return $this->pModifiers($node->type) . $this->pCommaSeparated($node->props) . ';';
    }

    public function pStmt_PropertyProperty(Stmt\PropertyProperty $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return '$' . $node->name
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    private function collectVars($node)
    {
        $vars = array();
        if (is_array($node) === true) {
            $nodes = $node;
        } elseif (method_exists($node, 'getIterator') === true) {
            $nodes = $node->getIterator();
        } else {
            return $vars;
        }

        foreach ($nodes as $stmt) {
            if ($stmt instanceof Expr\Assign) {
                if (($stmt->var instanceof Expr\PropertyFetch) === false) {
                    $vars[] = $stmt->var->name;
                }
            } elseif ($stmt instanceof Stmt\Foreach_) {
                if (null !== $stmt->keyVar) {
                    $vars[] = $stmt->keyVar->name;
                }
                $vars[] = $stmt->valueVar->name;
            }  elseif ($stmt instanceof Stmt\If_) {
                if ($stmt->right instanceof Expr\Assign) {
                    $vars[] = $stmt->right->var->name;
                }

                if ($stmt->left instanceof Expr\Assign) {
                    $vars[] = $stmt->left->var->name;
                }
            } elseif ($stmt instanceof Stmt\Catch_) {
                $vars[] = $stmt->var;
            }

            $vars = array_merge($vars, $this->collectVars($stmt));
        }

        $vars = array_map(array($this, 'replaceReservedWords'), $vars);

        return $vars;
    }

    private function hasReturnStatement($node)
    {
        $hasReturn = false;
        if (is_array($node) === true) {
            $nodes = $node;
        } elseif (method_exists($node, 'getIterator') === true) {
            $nodes = $node->getIterator();
        } else {
            return $hasReturn;
        }

        foreach ($nodes as $stmt) {
            if ($stmt instanceof Stmt\Return_) {
                $hasReturn = true;
                return $hasReturn;
            }

            $hasReturn = $this->hasReturnStatement($stmt);
        }

        return $hasReturn;
    }

    public function pStmt_ClassMethod(Stmt\ClassMethod $node) {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        $types = $this->typeFinder->getTypes($node, $this->actualNamespace, $this->use, $this->classes);
        $this->lastMethodConverted = $node->name;

        $stmt = $this->pModifiers($node->type) . 'function ' . ($node->byRef ? '&' : '') . $node->name . '(';
        $varsInMethodSign = array();

        if (isset($types['params']) === true) {
            $params = array();
            foreach ($types['params'] as $type) {
                $varsInMethodSign[] = $type['name'];
                $stringType = $this->printType($type);
                $params[] = ((!empty($stringType)) ? $stringType . ' ' : '') . '' . $type['name'] . ( ($type['default'] === null) ? '' : ' = ' . $this->p($type['default']));
            }

            $stmt .= implode(', ', $params);
        }

        $stmt .= ")";

        $hasReturn = $this->hasReturnStatement($node);
        if (array_key_exists('return', $types) === false && $this->haveReturnTag($node) === false && $hasReturn === false) {
            $stmt .= ' -> void';
        } elseif(array_key_exists('return', $types) === true && empty($types['return']['type']['value']) === false) {
            $stmt .= ' -> ' . $this->printType($types['return']);
        }

        $var = '';
        $vars  = array_diff(array_unique(array_filter($this->collectVars($node))), $varsInMethodSign);
        if (!empty($vars)) {
            $var .= "\n    var " . implode(', ', $vars) . ";\n";
        }

        $stmt .= (null !== $node->stmts ? "\n{" . $var . $this->pStmts($node->stmts) . "\n}" : ';') . "\n";

        return $stmt;
    }

    private function haveReturnTag(Stmt\ClassMethod $node)
    {
        if (empty($node->stmts) === false) {
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof Stmt\Return_) {
                    return true;
                }
            }
        }

        return false;
    }

    private function printType($type)
    {
        if (isset($type['type']) === false) {
            return '';
        }
        if (isset($type['type']['isClass']) === false) {
            throw new \Exception('isClass not found');
        }
        if (isset($type['type']['value']) === false) {
            throw new \Exception('value not found');
        }
        return ($type['type']['isClass'] === true) ? '<' . $type['type']['value'] . '>' : $type['type']['value'];
    }

    public function pStmt_ClassConst(Stmt\ClassConst $node) {
        return 'const ' . $this->pCommaSeparated($node->consts) . ';';
    }

    public function pStmt_Function(Stmt\Function_ $node) {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . $this->pCommaSeparated($node->params) . ')'
             . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Const(Stmt\Const_ $node) {
        return 'const ' . $this->pCommaSeparated($node->consts) . ';';
    }

    public function pStmt_Declare(Stmt\Declare_ $node) {
        return 'declare (' . $this->pCommaSeparated($node->declares) . ') {'
             . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_DeclareDeclare(Stmt\DeclareDeclare $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return $node->key . ' = ' . $this->p($node->value);
    }

    private function isBinaryOp($node)
    {
        return is_object($node) && strstr(get_class($node), 'PhpParser\Node\Expr\BinaryOp\\') !== false;
    }

    private function isScalar($node)
    {
        return is_object($node) && strstr(get_class($node), 'PhpParser\Node\Scalar\\') !== false;
    }

    private function transformAssignInCondition($node)
    {
        if ($this->isBinaryOp($node) !== false) {
            if ($node->right instanceof Assign) {
                $node->right = $this->transformAssignInCondition($node->right);
            } elseif ($node->right instanceof Expr\Array_) {
                $node->right = new Expr\Variable('tmp');
            } elseif ($this->isBinaryOp($node->right) !== false) {
                $node->right = $this->transformAssignInCondition($node->right);
            }
            if ($node->left instanceof Expr\Array_) {
                $node->left = new Expr\Variable('tmp');
            } elseif ($this->isBinaryOp($node->left) !== false) {
                $node->left = $this->transformAssignInCondition($node->left);
            } elseif ($node->left instanceof Expr\ConstFetch || $this->isScalar($node->left) !== false) {  // this is yoda ! invert condition
                $left = $node->left;
                $right = $node->right;
                $node->left = $right;
                $node->right = $left;
            }

        } elseif ($node instanceof Assign) {

            if ($this->isBinaryOp($node->expr) !== false) {
                $returned = $this->transformAssignInCondition($node->expr);

                if ($this->isBinaryOp($returned) !== false) {
                    $returned->left = $node->var;
                    $finalNode = $returned;
                }
            } else {
                $finalNode = $node->var;
            }

            return $finalNode;
        }

        return $node;
    }

    private function collectAssignInCondition($node, $collected = '')
    {
        if ($this->isBinaryOp($node) !== false) {
            if ($node->right instanceof Assign) {
                $collected = $this->collectAssignInCondition($node->right, $collected);
            } elseif ($node->right instanceof Expr\Array_) {
                $collected .= "var tmp;\nlet tmp = " . $this->p($node->right) . ";\n";
            }
            if ($node->left instanceof Expr\Array_) {
                $collected .= "var tmp;\nlet tmp = " . $this->p($node->left) . ";\n";
            }
        } elseif ($node instanceof Assign) {
            $rightCollected = '';
            $tmpNode = clone $node;
            if ($this->isBinaryOp($tmpNode->expr) !== false) {
                $rightCollected = $this->collectAssignInCondition($tmpNode->expr, $collected);
                $tmpNode->expr = $tmpNode->expr->left;
            }

            $collected .= $this->pExpr_Assign($tmpNode) . ";\n" . $rightCollected;
        }

        return $collected;
    }

    // Control flow

    public function pStmt_If(Stmt\If_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        $condition = clone $node;
        $collected = $this->collectAssignInCondition($condition->cond);
        $node->cond = $this->transformAssignInCondition($node->cond);

        if (empty($node->stmts)) {
            $node->stmts = array(new Stmt\Echo_(array(new Scalar\String("not allowed"))));
            $this->logger->logNode('Empty if not allowed, add "echo not allowed"', $node, $this->fullClass);
        }

        return $collected . 'if ' . $this->p($node->cond) . ' {'
             . $this->pStmts($node->stmts) . "\n" . '}'
             . $this->pImplode($node->elseifs)
             . (null !== $node->else ? $this->p($node->else) : '');
    }

    public function pStmt_ElseIf(Stmt\ElseIf_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return ' elseif ' . $this->p($node->cond) . ' {'
             . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Else(Stmt\Else_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return ' else {' . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_For(Stmt\For_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return 'for '
             . $this->pCommaSeparated($node->init) . ';' . (!empty($node->cond) ? ' ' : '')
             . $this->pCommaSeparated($node->cond) . ';' . (!empty($node->loop) ? ' ' : '')
             . $this->pCommaSeparated($node->loop)
             . ' {' . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Foreach(Stmt\Foreach_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return 'for ' . (null !== $node->keyVar ? $this->p($node->keyVar) . ', ' : '') . $this->p($node->valueVar) .
               ' in ' . $this->p($node->expr) . ' {' .
               $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_While(Stmt\While_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return 'while (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Do(Stmt\Do_ $node) {
        $condition = clone $node;
        $collected = $this->collectAssignInCondition($condition->cond);
        $collected = !empty($collected) ? "\n" . $collected : $collected;
        $node->cond = $this->transformAssignInCondition($node->cond);

        return 'do {' . $this->pStmts($node->stmts) . $collected . "\n"
             . '} while (' . $this->p($node->cond) . ');';
    }

    private function removeBreakStmt($case)
    {
        if (is_array($case->stmts) && !empty($case->stmts)) {
            $key = array_keys($case->stmts);

            $breakStmt = $case->stmts[end($key)];

            if ($breakStmt instanceof \PhpParser\Node\Stmt\Break_) {
                unset($case->stmts[end($key)]);
            }
        }

        return $case;
    }

    private function convertSwitchToIfelse(Stmt\Switch_ $node)
    {
        $stmt = '';
        $ifDefined = false;
        $left = null;

        foreach ($node->cases as $case) {
            $case = $this->removeBreakStmt($case);
            if (end($node->cases) === $case) {
                $stmt .= $this->pStmt_Else(new \PhpParser\Node\Stmt\Else_($case->stmts));
            } else {
                if (empty($case->stmts)) { // concatene empty statement
                    if ($left !== null) {
                        $left = new BinaryOp\BooleanOr($left, $case->cond);
                    } else {
                        $left = $case->cond;
                    }
                } elseif ($ifDefined === false) {
                    if ($left !== null) {
                        $lastLeft = new BinaryOp\BooleanOr($left, $case->cond);
                        $stmt .= $this->pStmt_If(new \PhpParser\Node\Stmt\If_($lastLeft, array('stmts' => $case->stmts)));
                        $left = null;
                    } else {
                        $stmt .= $this->pStmt_If(new \PhpParser\Node\Stmt\If_($case->cond, array('stmts' => $case->stmts)));
                    }
                    $ifDefined = true;
                } else {
                    if ($left !== null) {
                        $lastLeft = new BinaryOp\BooleanOr($left, $case->cond);
                        $stmt .= $this->pStmt_Elseif(new \PhpParser\Node\Stmt\Elseif_($lastLeft, $case->stmts));
                        $left = null;
                    } else {
                        $stmt .= $this->pStmt_Elseif(new \PhpParser\Node\Stmt\Elseif_($case->cond, $case->stmts));
                    }
                }
            }
        }

        return $stmt;
    }

    public function pStmt_Switch(Stmt\Switch_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        $transformToIf = false;
        foreach ($node->cases as $case) {
            if (($case->cond instanceof \PhpParser\Node\Scalar\String) === false && $case->cond !== null) {
                $transformToIf = true;
            }
        }

        if ($transformToIf === true) {
            return $this->convertSwitchToIfelse($node);
        } else {
            return 'switch (' . $this->p($node->cond) . ') {'
             . $this->pStmts($node->cases) . "\n" . '}';
        }
    }

    public function pStmt_Case(Stmt\Case_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return (null !== $node->cond ? 'case ' . $this->p($node->cond) : 'default') . ':'
             . $this->pStmts($node->stmts);
    }

    public function pStmt_TryCatch(Stmt\TryCatch $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return 'try {' . $this->pStmts($node->stmts) . "\n" . '}'
             . $this->pImplode($node->catches)
             . ($node->finallyStmts !== null
                ? ' finally {' . $this->pStmts($node->finallyStmts) . "\n" . '}'
                : '');
    }

    public function pStmt_Catch(Stmt\Catch_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return ' catch ' . $this->p($node->type) . ', ' . $node->var . ' {'
             . $this->pStmts($node->stmts) . "\n" . '}';
    }

    public function pStmt_Break(Stmt\Break_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return 'break' . ($node->num !== null ? ' ' . $this->p($node->num) : '') . ';';
    }

    public function pStmt_Continue(Stmt\Continue_ $node)
    {
        if ($node->num !== null) {
            $this->logger->logNode('"continue $number;" no supported in zephir', $node, $this->fullClass);
        }
        return 'continue;';
    }

    public function pStmt_Return(Stmt\Return_ $node) {
        return 'return' . (null !== $node->expr ? ' ' . $this->p($node->expr) : '') . ';';
    }

    public function pStmt_Throw(Stmt\Throw_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return 'throw ' . $this->p($node->expr) . ';';
    }

    public function pStmt_Label(Stmt\Label $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        return $node->name . ':';
    }

    public function pStmt_Goto(Stmt\Goto_ $node)
    {
        $this->logger->logNode('goto no supported in zephir', $node, $this->fullClass);
        return '';
    }

    // Other

    public function pStmt_Echo(Stmt\Echo_ $node) {
        return 'echo ' . $this->pCommaSeparated($node->exprs) . ';';
    }

    public function pStmt_Static(Stmt\Static_ $node) {
        return 'static ' . $this->pCommaSeparated($node->vars) . ';';
    }

    public function pStmt_Global(Stmt\Global_ $node) {
        return 'global ' . $this->pCommaSeparated($node->vars) . ';';
    }

    public function pStmt_StaticVar(Stmt\StaticVar $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        return '$' . $node->name
             . (null !== $node->default ? ' = ' . $this->p($node->default) : '');
    }

    public function pStmt_Unset(Stmt\Unset_ $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);
        $unset = '';
        foreach ($node->vars as $var) {
            $unset .= 'unset(' . $this->p($var) . ');' . "\n";
        }
        return $unset;
    }

    public function pStmt_InlineHTML(Stmt\InlineHTML $node) {
        $this->logger->logNode("Forbiden inline html", $node, $this->fullClass);
    }

    public function pStmt_HaltCompiler(Stmt\HaltCompiler $node) {
        return '__halt_compiler();' . $node->remaining;
    }

    // Helpers

    public function pObjectProperty($node)
    {
        if ($node instanceof Expr) {
            return '{' . $this->p($node) . '}';
        } else {
            return $node;
        }
    }

    public function pModifiers($modifiers) {
        return ($modifiers & Stmt\Class_::MODIFIER_PUBLIC    ? 'public '    : '')
             . ($modifiers & Stmt\Class_::MODIFIER_PROTECTED ? 'protected ' : '')
             . ($modifiers & Stmt\Class_::MODIFIER_PRIVATE   ? 'protected '   : '') // due to #issues/251
             . ($modifiers & Stmt\Class_::MODIFIER_STATIC    ? 'static '    : '')
             . ($modifiers & Stmt\Class_::MODIFIER_ABSTRACT  ? 'abstract '  : '')
             . ($modifiers & Stmt\Class_::MODIFIER_FINAL     ? 'final '     : '');
    }

    public function pEncapsList(array $encapsList, $quote)
    {
        $return = '';
        foreach ($encapsList as $element) {
            if (is_string($element)) {
                $return .= addcslashes($element, "\n\r\t\f\v$" . $quote . "\\");
            } else {
                $return .= '{' . $this->p($element) . '}';
            }
        }

        return $return;
    }

    public function pVarOrNewExpr(Node $node)
    {
        $this->logger->trace(__METHOD__ . ' ' . __LINE__, $node, $this->fullClass);

        if ($node instanceof Expr\New_) {
            return '(' . $this->p($node) . ')';
        } else {
            return $this->p($node);
        }
    }
}
