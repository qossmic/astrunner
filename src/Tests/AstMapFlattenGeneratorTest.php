<?php


namespace SensioLabs\AstRunner\Tests;


use SensioLabs\AstRunner\AstMap;
use SensioLabs\AstRunner\AstParser\NikicPhpParser\NikicPhpParser;
use SensioLabs\AstRunner\AstRunner;
use SensioLabs\AstRunner\Tests\ArrayAsserts;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceA;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceD;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceE;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceA;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceD;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceE;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceWithNoiseA;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceWithNoiseB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceWithNoiseC;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA1;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA2;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceB;
use SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceC;
use Symfony\Component\EventDispatcher\EventDispatcher;

class InheritanceDependencyVisitorTest extends \PHPUnit_Framework_TestCase
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
            [new \SplFileInfo(__DIR__.'/Fixtures/BasicInheritance/'.$fixture.'.php')]
        );
    }

    private function getInheritedInherits($class, AstMap $astMap)
    {
        return array_values(array_map(
            function (AstMap\FlattenAstInherit $v) {
                return $v->__toString();
            },
            array_filter(
                $astMap->getClassInherits($class),
                function (AstMap\AstInheritInterface $v) {
                    return $v instanceof AstMap\FlattenAstInherit;
                }
            )
        ));
    }

    public function testBasicInheritance()
    {
        $astMap = $this->getAstMap('FixtureBasicInheritance');

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceA::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            ['SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB::7 (Extends))'],
            $this->getInheritedInherits(FixtureBasicInheritanceC::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC::8 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB::7 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC::8 (Extends))'
            ],
            $this->getInheritedInherits(FixtureBasicInheritanceD::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceD::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC::8 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB::7 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceB::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceD::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC::8 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceC::8 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceD::9 (Extends))'
            ],
            $this->getInheritedInherits(FixtureBasicInheritanceE::class, $astMap)
        );

    }

    public function testBasicInheritanceInterfaces()
    {
        $astMap = $this->getAstMap('FixtureBasicInheritanceInterfaces');

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceInterfaceA::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceInterfaceB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            ['SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB::7 (Extends))'],
            $this->getInheritedInherits(FixtureBasicInheritanceInterfaceC::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC::8 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB::7 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC::8 (Extends))'
            ],
            $this->getInheritedInherits(FixtureBasicInheritanceInterfaceD::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceA::6 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceD::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC::8 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB::7 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceB::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceD::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC::8 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceC::8 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceInterfaceD::9 (Extends))'
            ],
            $this->getInheritedInherits(FixtureBasicInheritanceInterfaceE::class, $astMap)
        );

    }

    public function testBasicMultipleInheritanceInterfaces()
    {
        $astMap = $this->getAstMap('MultipleInheritanceInterfaces');

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(MultipleInteritanceA1::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(MultipleInteritanceA2::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(MultipleInteritanceA::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA1::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA::8 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA2::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA::8 (Extends))'
            ],
            $this->getInheritedInherits(MultipleInteritanceB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA1::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceB::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA::8 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA1::8 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceB::9 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA2::7 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceB::9 (Extends) -> SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA::8 (Extends))',
                'SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceA::8 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\MultipleInteritanceB::9 (Extends))'
            ],
            $this->getInheritedInherits(MultipleInteritanceC::class, $astMap)
        );


    }

    public function testBasicMultipleInheritanceWithNoise()
    {
        $astMap = $this->getAstMap('FixtureBasicInheritanceWithNoise');

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceWithNoiseA::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            [],
            $this->getInheritedInherits(FixtureBasicInheritanceWithNoiseB::class, $astMap)
        );

        $this->assertArrayValuesEquals(
            ['SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceWithNoiseA::18 (Extends) (path: SensioLabs\AstRunner\Tests\Visitor\Fixtures\FixtureBasicInheritanceWithNoiseB::19 (Extends))'],
            $this->getInheritedInherits(FixtureBasicInheritanceWithNoiseC::class, $astMap)
        );

    }

}
