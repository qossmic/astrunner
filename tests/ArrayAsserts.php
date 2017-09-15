<?php 

namespace SensioLabs\AstRunner\Tests;

trait ArrayAsserts
{

    public function assertArrayValuesEquals(array $expected, array $value)
    {
        $expected = array_values($expected);
        $value = array_values($value);

        sort($expected);
        sort($value);

        $this->assertEquals($expected, $value);
    }

}