<?php 

namespace SensioLabs\AstRunner\AstMap;

class FlattenAstInherit implements AstInheritInterface
{
    /** @var AstInherit[] */
    private $path;

    /** @var AstInherit */
    private $inherit;

    /**
     * @param AstInherit $inherit
     * @param array $path
     */
    public function __construct(AstInherit $inherit, array $path)
    {
        $this->path = $path;
        $this->inherit = $inherit;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $buffer = '';
        foreach ($this->path as $v) {
            $buffer = "{$v->__toString()} -> " . $buffer;
        }
        return "{$this->inherit->__toString()} (path: ".rtrim($buffer, ' -> ').')';
    }

    /** @return AstInherit[] */
    public function getPath()
    {
        return $this->path;
    }

    /** @return string */
    public function getClassName()
    {
        return $this->inherit->getClassName();
    }

    /** @return int */
    public function getLine()
    {
        return $this->inherit->getLine();
    }

    /** @return int */
    public function getType()
    {
        return $this->inherit->getType();
    }

}
