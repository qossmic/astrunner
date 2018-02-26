<?php

namespace SensioLabs\AstRunner;

use SensioLabs\AstRunner\AstMap\AstInheritInterface;
use SensioLabs\AstRunner\AstMap\FlattenAstInherit;
use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstParserInterface;

class AstMap
{
    /**
     * @var AstClassReferenceInterface[]
     */
    private $astClassReferences = [];

    /**
     * @var AstFileReferenceInterface[]
     */
    private $astFileReferences = [];

    /**
     * @var AstParserInterface
     */
    private $astParser;

    public function __construct(AstParserInterface $astParser)
    {
        $this->astParser = $astParser;
    }

    public function addAstClassReference(AstClassReferenceInterface $astClassReference)
    {
        $this->astClassReferences[$astClassReference->getClassName()] = $astClassReference;
    }

    /**
     * @param AstClassReferenceInterface[] $astClassReferences
     */
    public function addAstClassReferences(array $astClassReferences)
    {
        foreach ($astClassReferences as $astClassReference) {
            $this->addAstClassReference($astClassReference);
        }
    }

    public function addAstFileReference(AstFileReferenceInterface $astFileReference)
    {
        $this->astFileReferences[$astFileReference->getFilepath()] = $astFileReference;
    }

    /**
     * @return AstClassReferenceInterface[]
     */
    public function getAstClassReferences(): array
    {
        return $this->astClassReferences;
    }

    /**
     * @return AstFileReferenceInterface[]
     */
    public function getAstFileReferences(): array
    {
        return $this->astFileReferences;
    }

    /**
     * @param string $className
     *
     * @return null|AstClassReferenceInterface
     */
    public function getClassReferenceByClassName(string $className)
    {
        return $this->astClassReferences[$className] ?? null;
    }

    /**
     * @param string $className
     *
     * @return AstInheritInterface[]
     */
    public function getClassInherits(string $className): array
    {
        $buffer = [];

        foreach ($this->astParser->findInheritanceByClassname($className) as $dep) {
            $buffer[] = $dep;
        }

        foreach ($this->astParser->findInheritanceByClassname($className) as $classInherit) {
            foreach ($this->resolveDepsRecursive($classInherit) as $dep) {
                $buffer[] = $dep;
            }
        }

        return $buffer;
    }

    /**
     * @param AstInheritInterface $inheritDependency
     * @param \ArrayObject|null   $alreadyResolved
     * @param \SplStack|null      $path
     *
     * @return AstInheritInterface[]
     */
    private function resolveDepsRecursive(
        AstInheritInterface $inheritDependency,
        \ArrayObject $alreadyResolved = null,
        \SplStack $path = null
    ): array {
        if (null === $alreadyResolved) {
            $alreadyResolved = new \ArrayObject();
        }
        if (null === $path) {
            $path = new \SplStack();
            $path->push($inheritDependency);
        }

        if (isset($alreadyResolved[$inheritDependency->getClassName()])) {
            $path->pop();

            return [];
        }

        $buffer = [];
        foreach ($this->astParser->findInheritanceByClassname($inheritDependency->getClassName()) as $inherit) {
            $alreadyResolved[$inheritDependency->getClassName()] = true;

            $buffer[] = new FlattenAstInherit($inherit, iterator_to_array($path));
            $path->push($inherit);
            foreach ($this->resolveDepsRecursive($inherit, $alreadyResolved, $path) as $dep) {
                $buffer[] = $dep;
            }
            unset($alreadyResolved[$inheritDependency->getClassName()]);
            $path->pop();
        }

        return $buffer;
    }
}
