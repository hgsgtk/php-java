<?php
namespace PHPJava\Compiler\Emulator\Mnemonics;

class _nop extends AbstractOperationCode implements OperationCodeInterface
{
    use \PHPJava\Compiler\Emulator\Traits\GeneralProcessor;

    public function execute(): void
    {
        // Nothing to do
    }
}
