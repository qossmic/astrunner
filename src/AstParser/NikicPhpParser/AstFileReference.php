<?php

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;

class AstFileReference implements AstFileReferenceInterface
{
    private $filepath;
    private $astClassReferences;

    public function __construct(string $filepath)
    {
        $this->filepath = $filepath;
    }

    /** @param AstClassReference[] $astClassReferences */
    public function setAstClassReferences(array $astClassReferences)
    {
        $this->astClassReferences = $astClassReferences;
    }

    /** @return mixed */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /** @return AstClassReference[] */
    public function getAstClassReferences()
    {
        return $this->astClassReferences;
    }
}
