<?php

namespace SensioLabs\AstRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class AstFileAnalyzedEvent extends Event
{
    private $file;

    /**
     * @param \SplFileInfo $file
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

}
