<?php
namespace PHPJava\Compiler\Emulator\Mnemonics;

class _iinc extends AbstractOperationCode implements OperationCodeInterface
{
    use \PHPJava\Compiler\Emulator\Traits\GeneralProcessor;

    public function execute(): void
    {
        // No effected.
    }
}
