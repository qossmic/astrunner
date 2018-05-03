<?php

namespace SensioLabs\AstRunner\AstParser;

use SensioLabs\AstRunner\AstMap\AstInheritInterface;

interface AstParserInterface extends AstReferenceInterface
{
    /**
     * @param mixed $data
     *
     * @return AstFileReferenceInterface
     */
    public function parse($data): AstFileReferenceInterface;

    /**
     * @param string $className
     *
     * @return AstInheritInterface[]
     */
    public function findInheritanceByClassname(string $className): array;
}
