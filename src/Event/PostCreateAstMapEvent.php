<?php

namespace SensioLabs\AstRunner\Event;

use SensioLabs\AstRunner\AstMap;
use Symfony\Component\EventDispatcher\Event;

class PostCreateAstMapEvent extends Event
{
    private $astMap;

    /**
     * PreCreateAstMap constructor.
     * @param $astMap
     */
    public function __construct(AstMap $astMap)
    {
        $this->astMap = $astMap;
    }

    /**
     * @return AstMap
     */
    public function getAstMap()
    {
        return $this->astMap;
    }

}
