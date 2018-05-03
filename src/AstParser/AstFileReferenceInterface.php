<?php

namespace SensioLabs\AstRunner\AstParser;

interface AstFileReferenceInterface
{
    public function getFilepath(): string;

    /**
     * @return AstClassReferenceInterface[]
     */
    public function getAstClassReferences(): array;
}
