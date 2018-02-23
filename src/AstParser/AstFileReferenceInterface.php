<?php

namespace SensioLabs\AstRunner\AstParser;

interface AstFileReferenceInterface
{
    public function getFilepath();

    /** @return AstClassReferenceInterface[] */
    public function getAstClassReferences();
}
