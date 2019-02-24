<?php
namespace PHPJava\Kernel\Mnemonics;

use PHPJava\Exceptions\NotImplementedException;
use PHPJava\Utilities\BinaryTool;
use PHPJava\Utilities\ClassResolver;
use PHPJava\Utilities\Formatter;

final class _getstatic implements OperationInterface
{
    use \PHPJava\Kernel\Core\Accumulator;
    use \PHPJava\Kernel\Core\ConstantPool;

    public function execute(): void
    {
        $cpInfo = $this->getConstantPool()->getEntries();
        
        $cp = $cpInfo[$this->readUnsignedShort()];

        $class = $cpInfo[$cpInfo[$cp->getClassIndex()]->getClassIndex()]->getString();

        $signature = Formatter::parseSignature($cpInfo[$cpInfo[$cp->getNameAndTypeIndex()]->getDescriptorIndex()]->getString());

        if (isset($signature[0]['class_name'])) {
            $className = ClassResolver::resolve($signature[0]['class_name']);
            $this->pushStack(new $className());
            return;
        }

        foreach ($this->javaClass->getFields() as $field) {
            if ($cpInfo[$field->getNameIndex()]->getString() === $cpInfo[$cpInfo[$cp->getNameAndTypeIndex()]->getNameIndex()]->getString()) {

                // push stack
                $fieldName = $cpInfo[$field->getNameIndex()]->getString();
                $this->pushStack($this->javaClassInvoker->getStaticFields()->get($fieldName));
                return;
            }
        }
            
        throw new \Exception('にゃ〜ん');
    }
}
