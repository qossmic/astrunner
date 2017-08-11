<?php

namespace SensioLabs\AstRunner;

use SensioLabs\AstRunner\AstMap\AstInheritInterface;
use SensioLabs\AstRunner\AstMap\FlattenAstInherit;
use SensioLabs\AstRunner\AstParser\AstClassReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstFileReferenceInterface;
use SensioLabs\AstRunner\AstParser\AstParserInterface;
use SensioLabs\AstRunner\AstParser\NikicPhpParser\AstClassReference;

class AstMap
{
    /** @var AstClassReference[] */
    private $astClassReferences = [];

    /** @var array */
    private $astFileReferences = [];

    /** @var AstParserInterface */
    private $astParser;

    public function __construct(AstParserInterface $astParser)
    {
        $this->astParser = $astParser;
    }

    public function addAstClassReference(AstClassReferenceInterface $astClassReference)
    {
        $this->astClassReferences[$astClassReference->getClassName()] = $astClassReference;
    }

    public function addAstFileReferences(AstFileReferenceInterface $astFileReference)
    {
        $this->astFileReferences[$astFileReference->getFilepath()] = $astFileReference;
    }

    /**
     * @return AstClassReferenceInterface[]
     */
    public function getAstClassReferences()
    {
        return $this->astClassReferences;
    }

    /**
     * @return AstFileReferenceInterface[]
     */
    public function getAstFileReferences()
    {
        return $this->astFileReferences;
    }

    /**
     * @param $className
     * @return null|AstClassReference
     */
    public function getClassReferenceByClassName($className)
    {
        if (!isset($this->astClassReferences[$className])) {
            return null;
        }

        return $this->astClassReferences[$className];
    }

    /**
     * @param $className
     * @return AstInheritInterface[]
     */
    public function getClassInherits($className)
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
     * @return array
     */
    private function resolveDepsRecursive(
        AstInheritInterface $inheritDependency,
        \ArrayObject $alreadyResolved = null,
        \SplStack $path = null
    ) {
        if ($alreadyResolved == null) {
            $alreadyResolved = new \ArrayObject();
        }
        if ($path == null) {
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
