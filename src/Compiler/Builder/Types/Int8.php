<?php
namespace PHPJava\Compiler\Builder\Types;

class Int8 implements TypeInterface
{
    public static function sizeOf()
    {
        return 1;
    }
}
