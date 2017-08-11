<?php

namespace SensioLabs\AstRunner;

use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstParserInterface;
use SensioLabs\AstRunner\Event\AstFileAnalyzedEvent;
use SensioLabs\AstRunner\Event\AstFileSyntaxErrorEvent;
use SensioLabs\AstRunner\Event\PostCreateAstMapEvent;
use SensioLabs\AstRunner\Event\PreCreateAstMapEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AstRunner
{
    /**
     * @param AstParserInterface       $astParser
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $files
     *
     * @return AstMap
     */
    public function createAstMapByFiles(
        AstParserInterface $astParser,
        EventDispatcherInterface $dispatcher,
        array $files
    ) {
        $dispatcher->dispatch(PreCreateAstMapEvent::class, new PreCreateAstMapEvent(count($files)));

        $astMap = new AstMap($astParser);

        foreach ($files as $file) {

            try {
                foreach ($astParser->parse($file) as $astReference) {
                    if ($astReference instanceof AstClassReferenceInterface) {
                        $astMap->addAstClassReference($astReference);
                    } elseif ($astReference instanceof AstFileReferenceInterface) {
                        $astMap->addAstFileReferences($astReference);
                    } else {
                        throw new \LogicException('unknown AST Type.');
                    }
                }

                $dispatcher->dispatch(AstFileAnalyzedEvent::class, new AstFileAnalyzedEvent($file));
            } catch (\PhpParser\Error $e) {
                $dispatcher->dispatch(
                    AstFileSyntaxErrorEvent::class,
                    new AstFileSyntaxErrorEvent($file, $e->getMessage())
                );
            }
        }

        $dispatcher->dispatch(PostCreateAstMapEvent::class, new PostCreateAstMapEvent($astMap));

        return $astMap;
    }
}
