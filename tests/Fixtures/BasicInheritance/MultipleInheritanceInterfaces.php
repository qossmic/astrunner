<?php 

namespace Tests\SensioLabs\AstRunner\Visitor\Fixtures;

interface MultipleInteritanceA1 { } // []
interface MultipleInteritanceA2 { } // []
interface MultipleInteritanceA extends MultipleInteritanceA1, MultipleInteritanceA2 { } // []
interface MultipleInteritanceB extends MultipleInteritanceA, MultipleInteritanceA1 {} // [A2]
interface MultipleInteritanceC extends MultipleInteritanceB {} // [MultipleInteritanceA, MultipleInteritanceA1, MultipleInteritanceA1, MultipleInteritanceA2]
