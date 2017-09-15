<?php


namespace SensioLabs\AstRunner\Tests\Visitor;


use SensioLabs\AstRunner\AstMap;
use SensioLabs\AstRunner\AstParser\NikicPhpParser\NikicPhpParser;
use SensioLabs\AstRunner\AstRunner;
use SensioLabs\AstRunner\Tests\ArrayAsserts;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassC;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitA;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitC;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitClass;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitD;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AstMapGeneratorTest extends \PHPUnit_Framework_TestCase
{
    use ArrayAsserts;

    /**
     * @param $fixture
     * @return AstMap
     */
    private function getAstMap($fixture)
    {
        return (new AstRunner())->createAstMapByFiles(
            new NikicPhpParser(),
            new EventDispatcher(),
            [new \SplFileInfo(__DIR__ . '/Fixtures/BasicDependency/' . $fixture . '.php')]
        );
    }

    private function getDirectInherits($class, AstMap $astMap)
    {
        return array_map(
            function (AstMap\AstInherit $v) {
                return $v->__toString();
            },
            array_filter(
                $astMap->getClassInherits($class),
                function (AstMap\AstInheritInterface $v) {
                    return $v instanceof AstMap\AstInherit;
                }
            )
        );
    }

    public function testBasicDependencyClass()
    {
        $astMap = $this->getAstMap('BasicDependencyClass');

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassA::9 (Extends)',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassInterfaceA::9 (Implements)'
            ],
            $this->getDirectInherits(BasicDependencyClassB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassInterfaceA::13 (Implements)',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyClassInterfaceB::13 (Implements)'
            ],
            $this->getDirectInherits(BasicDependencyClassC::class, $astMap)
        );
    }

    public function testBasicTraitsClass()
    {
        $astMap = $this->getAstMap('BasicDependencyTraits');

        $this->assertArrayValuesEquals(
            [],
            $this->getDirectInherits(BasicDependencyTraitA::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getDirectInherits(BasicDependencyTraitB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            ['SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitB::7 (Uses)'],
            $this->getDirectInherits(BasicDependencyTraitC::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitA::10 (Uses)',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitB::11 (Uses)'
            ],
            $this->getDirectInherits(BasicDependencyTraitD::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            ['SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency\BasicDependencyTraitA::15 (Uses)'],
            $this->getDirectInherits(BasicDependencyTraitClass::class, $astMap)
        );
    }
}
