<?php
namespace PHPJava\Compiler\Lang\Assembler\Traits\Enhancer\Operation;

use PHPJava\Compiler\Builder\Generator\Operation\Operand;
use PHPJava\Compiler\Builder\Generator\Operation\Operation;
use PHPJava\Compiler\Builder\Signatures\Descriptor;
use PHPJava\Compiler\Builder\Types\Uint16;
use PHPJava\Compiler\Builder\Types\Uint8;
use PHPJava\Compiler\Lang\Assembler\Enhancer\ConstantPoolEnhancer;
use PHPJava\Exceptions\AssembleStructureException;
use PHPJava\Kernel\Maps\OpCode;
use PHPJava\Kernel\Types\_Int;
use PHPJava\Packages\java\lang\_String;
use PHPJava\Utilities\ArrayTool;

/**
 * @method ConstantPoolEnhancer getEnhancedConstantPool()
 * @method array assembleLoadNumber(int $value, string &$type = null)
 */
trait FieldAssignable
{
    public function assembleAssignStaticField(string $class, string $fieldName, $value, string $signature): array
    {
        $operations = [];

        switch ($signature) {
            case _String::class:
                $this->getEnhancedConstantPool()
                    ->addString($value);
                $operations[] = Operation::create(
                    OpCode::_ldc,
                    Operand::factory(
                        Uint8::class,
                        $this->getEnhancedConstantPool()
                            ->findString($value)
                    )
                );
                break;
            case _Int::class:
                ArrayTool::concat(
                    $operations,
                    ...$this->assembleLoadNumber(
                        $value
                    )
                );
                break;
            default:
                throw new AssembleStructureException(
                    'Unsupported signature type: ' . $signature
                );
        }

        $operations[] = Operation::create(
            OpCode::_putstatic,
            Operand::factory(
                Uint16::class,
                $this->getEnhancedConstantPool()
                    ->findField(
                        $class,
                        $fieldName,
                        (new Descriptor())
                            ->addArgument($signature)
                            ->make()
                    )
            )
        );
        return $operations;
    }
}
