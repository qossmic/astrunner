<?php

namespace SensioLabs\AstRunner\AstParser;

interface AstClassReferenceInterface extends AstReferenceInterface
{

    public function getFileReference();

    public function getClassName();

}