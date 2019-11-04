<?php

namespace PHPJava\Compiler\Lang\Assembler\Bundler\Packages;

use PHPJava\Compiler\Builder\Collection\ConstantPool;
use PHPJava\Compiler\Builder\Finder\ConstantPoolFinder;
use PHPJava\Compiler\Lang\Assembler\Bundler\AbstractBundler;
use PHPJava\Compiler\Lang\Assembler\Bundler\PackageBundlerInterface;
use PHPJava\Core\JVM\Parameters\Runtime;
use PHPJava\Kernel\Types\_Void;

abstract class AbstractPackageBundler implements PackageBundlerInterface
{
    /**
     * @var AbstractBundler
     */
    protected $bundler;

    public static function factory(AbstractBundler $bundler): PackageBundlerInterface
    {
        return new static($bundler);
    }

    public function __construct(AbstractBundler $bundler)
    {
        $this->bundler = $bundler;
    }

    public function getDefinedConstants(): array
    {
        $constants = [];
        $reflectionClass = new \ReflectionClass($this);
        foreach ($reflectionClass->getConstants() as $name => $value) {
            $constantInfo = new \ReflectionClassConstant($this, $name);
            try {
                if ($phpDocument = $constantInfo->getDocComment()) {
                    $documentBlock = \phpDocumentor\Reflection\DocBlockFactory::createInstance()
                        ->create($phpDocument);

                    // Need @export flag.
                    if (!$documentBlock->hasTag('export') || !$documentBlock->hasTag('var')) {
                        continue;
                    }

                    /**
                     * @var \phpDocumentor\Reflection\DocBlock\Tags\Var_ $varType
                     */
                    $varType = current($documentBlock->getTagsByName('var'));
                    $constants[] = [
                        $name,
                        $value,
                        ltrim((string) $varType->getType(), '\\'),
                    ];
                }
            } catch (\InvalidArgumentException $e) {
            }
        }

        return $constants;
    }

    public function getDefinedMethods(): array
    {
        $methods = [];
        $reflectionClass = new \ReflectionClass($this);
        foreach ($reflectionClass->getMethods() as $method) {
//            $arguments = [];
//            $return = _Void::class;
//
//            $className = str_replace(Runtime::BUILD_PACKAGE_NAMESPACE, '', get_class($this));
//            $mappedClassName = Runtime::BUILD_PACKAGE_NAMESPACE . 'Map\\' . $className;
//
//            $methodName = null;
//            $arguments = null;
//            $return = null;
//            foreach ($mappedClassName::METHOD_MAP as $mappedMethod) {
//                [$targetedMethodName, $targetedArguments, $targetedReturn] = $mappedMethod;
//                if ($method->getName() !== $targetedMethodName) {
//                    continue;
//                }
//
//                $methodName = $targetedMethodName;
//                $arguments = $targetedArguments;
//                $return = $targetedReturn;
//            }
//
//            if ($methodName === null) {
//                continue;
//            }
//
//            $methods[] = [
//                $method->getName(),
//                $arguments,
//                $return,
//            ];
        }
        return $methods;
    }

    public function getBundler(): AbstractBundler
    {
        return $this->bundler;
    }

    public function getConstantPool(): ConstantPool
    {
        return $this->getBundler()->getConstantPool();
    }

    public function getConstantPoolFinder(): ConstantPoolFinder
    {
        return $this->getBundler()->getConstantPoolFinder();
    }
}
