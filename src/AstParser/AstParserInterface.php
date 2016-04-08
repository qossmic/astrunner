<?php

namespace SensioLabs\AstRunner\AstParser;

use SensioLabs\AstRunner\AstMap\AstInheritInterface;

interface AstParserInterface extends AstReferenceInterface
{
    /**
     * @param $data
     * @return AstClassReferenceInterface[]|AstFileReferenceInterface[]
     */
    public function parse($data);

    /**
     * @param $className
     * @return AstInheritInterface[]
     */
    public function findInheritanceByClassname($className);
}