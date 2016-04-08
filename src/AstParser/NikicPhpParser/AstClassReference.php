<?php

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;

class AstClassReference implements AstClassReferenceInterface
{
    private $fileReference;

    private $className;

    /**
     * AstClassReference constructor.
     * @param $filepath
     * @param $className
     */
    public function __construct($className, AstFileReference $fileReference = null)
    {
        $this->fileReference = $fileReference;
        $this->className = $className;
    }

    /**
     * @return AstFileReference
     */
    public function getFileReference()
    {
        return $this->fileReference;
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->className;
    }

}
