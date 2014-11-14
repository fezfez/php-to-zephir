<?php

namespace Code\Oop;

class Attribute
{
    public $publicAttr = array();
    protected $protectedAttr = array();
    private $privateAttr = array();

    public static $publicStaticAttr = array();
    protected static $protectedStaticAttr = array();
    private static $privateStaticAttr = array();

    const MY_CONST = array();

    public function test()
    {
    	$test = $this->publicAttr;
    	$test = $this->protectedAttr;
    	$test = $this->privateAttr;

    	$test = self::publicStaticAttr;
    	$test = self::protectedStaticAttr;
    	$test = self::privateStaticAttr;

    	$test = self::MY_CONST;
    }
}