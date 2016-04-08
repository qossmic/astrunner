<?php 

namespace SensioLabs\AstRunner\AstMap;

class AstInherit implements AstInheritInterface
{
    private $className;

    private $line;

    private $type;

    /**
     * @param $className
     * @param $line
     * @param $type
     */
    private function __construct($className, $line, $type)
    {
        $this->className = $className;
        $this->line = $line;
        $this->type = $type;
    }

    public static function newExtends($className, $line)
    {
        return new self($className, $line, self::TYPE_EXTENDS);
    }

    public static function newImplements($className, $line)
    {
        return new self($className, $line, self::TYPE_IMPLEMENTS);
    }

    public static function newUses($className, $line)
    {
        return new self($className, $line, self::TYPE_USES);
    }

    public function __toString()
    {
        switch ($this->type) {
            case static::TYPE_EXTENDS:
                $type = 'Extends';
                break;
            case static::TYPE_USES:
                $type = 'Uses';
                break;
            case static::TYPE_IMPLEMENTS:
                $type = 'Implements';
                break;
            default:
                $type = "Unknown";;
        }

        return "{$this->className}::{$this->line} ($type)";
    }

    /** @return string */
    public function getClassName()
    {
        return $this->className;
    }

    /** @return int */
    public function getLine()
    {
        return $this->line;
    }

    /** @return int */
    public function getType()
    {
        return $this->type;
    }

    /** @return AstInheritInterface[] */
    public function getPath()
    {
        return [];
    }

}
