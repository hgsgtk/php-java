<?php
namespace PHPJava\Compiler\Builder;

use PHPJava\Compiler\Builder\Finder\Result\FinderResultInterface;
use PHPJava\Compiler\Builder\Maps\EntryMap;

class Field implements BuilderInterface, EntryInterface
{
    private $attributes = [];
    private $accessFlags = 0;
    protected $value;

    /**
     * @var FinderResultInterface
     */
    private $nameIndex;

    /**
     * @var FinderResultInterface
     */
    private $descriptorIndex;

    public function __construct(
        int $accessFlags,
        FinderResultInterface $nameIndex,
        FinderResultInterface $descriptorIndex
    ) {
        $this->accessFlags = $accessFlags;
        $this->nameIndex = $nameIndex;
        $this->descriptorIndex = $descriptorIndex;
    }

    public function getAccessFlags(): int
    {
        return $this->accessFlags;
    }

    public function getNameIndex(): int
    {
        /**
         * @var EntryMap $result
         */
        $result = $this->nameIndex->getResult();
        return $result
            ->getEntryIndex();
    }

    public function getDescriptorIndex(): int
    {
        /**
         * @var EntryMap $result
         */
        $result = $this->descriptorIndex->getResult();
        return $result
            ->getEntryIndex();
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
