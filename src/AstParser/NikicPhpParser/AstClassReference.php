<?php

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;

class AstClassReference implements AstClassReferenceInterface
{
    private $className;
    private $fileReference;

    public function __construct(string $className, AstFileReference $fileReference = null)
    {
        $this->className = $className;
        $this->fileReference = $fileReference;
    }

    public function getFileReference(): ?AstFileReferenceInterface
    {
        return $this->fileReference;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
