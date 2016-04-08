<?php

namespace SensioLabs\AstRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class AstFileSyntaxErrorEvent extends Event
{
    private $file;

    private $syntaxError;

    /**
     * AstFileSyntaxErrorEvent constructor.
     * @param $filepath
     * @param $syntaxError
     */
    public function __construct(\SplFileInfo $file, $syntaxError)
    {
        $this->file = $file;
        $this->syntaxError = $syntaxError;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getSyntaxError()
    {
        return $this->syntaxError;
    }

}
