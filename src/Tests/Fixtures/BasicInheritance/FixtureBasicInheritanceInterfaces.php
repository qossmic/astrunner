<?php 

namespace SensioLabs\AstRunner\Tests\Visitor\Fixtures;

interface FixtureBasicInheritanceInterfaceA { }
interface FixtureBasicInheritanceInterfaceB extends FixtureBasicInheritanceInterfaceA { }
interface FixtureBasicInheritanceInterfaceC extends FixtureBasicInheritanceInterfaceB { }
interface FixtureBasicInheritanceInterfaceD extends FixtureBasicInheritanceInterfaceC { }
interface FixtureBasicInheritanceInterfaceE extends FixtureBasicInheritanceInterfaceD { }