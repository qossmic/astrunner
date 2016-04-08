<?php

namespace SensioLabs\AstRunner\AstMap;

interface AstInheritInterface
{
    const TYPE_EXTENDS = 1;
    const TYPE_IMPLEMENTS = 2;
    const TYPE_USES = 3;

    public function __toString();

    /** @return string */
    public function getClassName();

    /** @return int */
    public function getLine();

    /** @return int */
    public function getType();

    /** @return AstInheritInterface[] */
    public function getPath();

}