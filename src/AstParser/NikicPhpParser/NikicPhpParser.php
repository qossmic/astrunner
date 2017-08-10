<?php

namespace SensioLabs\AstRunner\AstParser\NikicPhpParser;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstParserInterface;

class NikicPhpParser implements AstParserInterface
{
    private static $parser = null;
    private static $traverser = null;
    private static $inheritanceByClassnameMap = [];
    private static $fileAstMap = [];
    private static $classAstMap = [];

    /**
     * @return null|\PhpParser\Parser
     */
    private static function getParser()
    {
        if (self::$parser) {
            return self::$parser;
        }

        self::$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        return self::$parser;
    }

    /**
     * @return \PhpParser\NodeTraverser
     */
    private static function getTraverser()
    {
        if (self::$traverser) {
            return self::$traverser;
        }

        self::$traverser = new NodeTraverser();
        self::$traverser->addVisitor(new NameResolver());

        return self::$traverser;
    }

    /**
     * @param \SplFileInfo $data
     * @return bool
     */
    public function supports($data)
    {
        if (!$data instanceof \SplFileInfo) {
            return false;
        }

        return strtolower($data->getExtension()) == "php";
    }

    /**
     * @param \SplFileInfo $data
     * @return AstClassReferenceInterface[]
     */
    public function parse($data)
    {
        if (!$this->supports($data)) {
            throw new \LogicException();
        }

        $ast = self::getTraverser()->traverse(
            self::getParser()->parse(
                file_get_contents($data->getPathname())
            )
        );

        self::$fileAstMap[$data->getRealPath()] = $ast;

        $buffer = [];
        $fileReference = new AstFileReference($data->getRealPath());

        foreach (AstHelper::findClassLikeNodes($ast) as $classLikeNode) {
            $className = $classLikeNode->namespacedName->toString();
            $buffer[] = new AstClassReference($className, $fileReference);
            self::$classAstMap[$className] = $classLikeNode;
        }

        $fileReference->setAstClassReferences($buffer);

        $buffer[] = $fileReference;

        return $buffer;
    }

    /**
     * @param AstFileReferenceInterface $astReference
     * @return array
     */
    public function getAstByFile(AstFileReferenceInterface $astReference)
    {
        if (!isset(self::$fileAstMap[$astReference->getFilepath()])) {
            return [];
        }

        return self::$fileAstMap[$astReference->getFilepath()];
    }

    /**
     * @param $className
     * @return array
     */
    public function getAstForClassname($className)
    {
        if (!isset(self::$classAstMap[$className])) {
            return [];
        }

        return self::$classAstMap[$className];
    }

    public function findNodesOfType($nodes, $type)
    {
        $collectedNodes = [];

        foreach ((array)$nodes as $i => &$node) {
            if (is_array($node)) {
                $collectedNodes = array_merge($this->findNodesOfType($node, $type), $collectedNodes);
            } elseif ($node instanceof Node) {

                if (is_a($node, $type, true)) {
                    $collectedNodes[] = $node;
                }

                $collectedNodes = array_merge(
                    $this->findNodesOfType(
                        AstHelper::getSubNodes($node),
                        $type
                    ),
                    $collectedNodes
                );
            }
        }

        return $collectedNodes;
    }

    /**
     * @param $className
     * @return array|\SensioLabs\AstRunner\AstMap\AstInheritInterface[]
     */
    public function findInheritanceByClassname($className)
    {
        if (isset(self::$inheritanceByClassnameMap[$className])) {
            return self::$inheritanceByClassnameMap[$className];
        }

        if (!isset(self::$classAstMap[$className])) {
            return self::$inheritanceByClassnameMap[$className] = [];
        }

        return self::$inheritanceByClassnameMap[$className] = AstHelper::findInheritances(
            self::$classAstMap[$className]
        );
    }
}
