<?php

namespace SensioLabs\AstRunner\AstParser;

class AstParseResult
{
    private $astFileReference;
    private $astClassReferences;

    /**
     * @param AstFileReferenceInterface    $astFileReference
     * @param AstClassReferenceInterface[] $astClassReferences
     */
    public function __construct(AstFileReferenceInterface $astFileReference, array $astClassReferences)
    {
        $this->astFileReference = $astFileReference;
        $this->astClassReferences = $astClassReferences;
    }

    public function getAstFileReference(): AstFileReferenceInterface
    {
        return $this->astFileReference;
    }

    /**
     * @return AstClassReferenceInterface[]
     */
    public function getAstClassReferences(): array
    {
        return $this->astClassReferences;
    }
}
