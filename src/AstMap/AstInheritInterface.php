<?php

namespace SensioLabs\AstRunner\AstMap;

interface AstInheritInterface
{
    const TYPE_EXTENDS = 1;
    const TYPE_IMPLEMENTS = 2;
    const TYPE_USES = 3;

    public function __toString(): string;

    /** @return string */
    public function getClassName(): string;

    /** @return int */
    public function getLine(): int;

    /** @return int */
    public function getType(): int;

    /** @return AstInheritInterface[] */
    public function getPath(): array;
}
