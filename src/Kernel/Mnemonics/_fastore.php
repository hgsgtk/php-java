<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Kernel\Filters\Normalizer;
use PHPJava\Kernel\Types\_Float;
use PHPJava\Kernel\Types\Type;

final class _fastore extends AbstractOperationCode implements OperationCodeInterface
{
    public function getOperands(): ?Operands
    {
        parent::getOperands();
        if ($this->operands !== null) {
            return $this->operands;
        }
        return $this->operands = new Operands();
    }

    public function execute(): void
    {
        parent::execute();
        $value = $this->popFromOperandStack();
        $index = Normalizer::getPrimitiveValue($this->popFromOperandStack());

        /**
         * @var Type $arrayref
         */
        $arrayref = $this->popFromOperandStack();

        // The value is a ref.
        $arrayref[$index] = _Float::get($value);
    }
}
