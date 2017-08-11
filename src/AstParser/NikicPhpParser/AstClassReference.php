<?php

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;

class AstClassReference implements AstClassReferenceInterface
{
    private $className;
    private $fileReference;

    /**
     * @param string                $className
     * @param AstFileReference|null $fileReference
     */
    public function __construct($className, AstFileReference $fileReference = null)
    {
        $this->className = $className;
        $this->fileReference = $fileReference;
    }

    /**
     * @return AstFileReference|null
     */
    public function getFileReference()
    {
        return $this->fileReference;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

}
