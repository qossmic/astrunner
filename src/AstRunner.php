<?php

namespace SensioLabs\AstRunner;

use SensioLabs\AstRunner\AstParser\AstParserInterface;
use SensioLabs\AstRunner\Event\AstFileAnalyzedEvent;
use SensioLabs\AstRunner\Event\AstFileSyntaxErrorEvent;
use SensioLabs\AstRunner\Event\PostCreateAstMapEvent;
use SensioLabs\AstRunner\Event\PreCreateAstMapEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AstRunner
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param AstParserInterface $astParser
     * @param \SplFileInfo[]     $files
     *
     * @return AstMap
     */
    public function createAstMapByFiles(AstParserInterface $astParser, array $files): AstMap
    {
        $this->dispatcher->dispatch(PreCreateAstMapEvent::class, new PreCreateAstMapEvent(count($files)));

        $astMap = new AstMap($astParser);

        foreach ($files as $file) {
            try {
                $astMap->addAstFileReference($astParser->parse($file));

                $this->dispatcher->dispatch(AstFileAnalyzedEvent::class, new AstFileAnalyzedEvent($file));
            } catch (\PhpParser\Error $e) {
                $this->dispatcher->dispatch(
                    AstFileSyntaxErrorEvent::class,
                    new AstFileSyntaxErrorEvent($file, $e->getMessage())
                );
            }
        }

        $this->dispatcher->dispatch(PostCreateAstMapEvent::class, new PostCreateAstMapEvent($astMap));

        return $astMap;
    }
}
