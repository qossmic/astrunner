<?php 

namespace SensioLabs\AstRunner\Tests\Visitor\Fixtures\BasicDependency;

class BasicDependencyClassA {}
interface BasicDependencyClassInterfaceA {}
interface BasicDependencyClassInterfaceB {}

class BasicDependencyClassB extends BasicDependencyClassA implements BasicDependencyClassInterfaceA {

}

class BasicDependencyClassC implements BasicDependencyClassInterfaceA, BasicDependencyClassInterfaceB {

}
