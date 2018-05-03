<?php

namespace SensioLabs\AstRunner\AstParser;

interface AstClassReferenceInterface extends AstReferenceInterface
{
    /**
     * @return AstFileReferenceInterface|null
     */
    public function getFileReference();

    public function getClassName(): string;
}
