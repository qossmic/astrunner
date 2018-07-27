<?php

namespace SensioLabs\AstRunner\AstParser;

interface AstClassReferenceInterface extends AstReferenceInterface
{
    public function getFileReference(): ?AstFileReferenceInterface;

    public function getClassName(): string;
}
