<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

final class UploadValue implements \Graphpinator\Value\InputedValue
{
    use \Nette\SmartObject;

    private UploadType $type;
    private \Psr\Http\Message\UploadedFileInterface $rawValue;

    public function __construct(\Psr\Http\Message\UploadedFileInterface $rawValue)
    {
        $this->type = new UploadType();
        $this->rawValue = $rawValue;
    }

    public function getRawValue() : \Psr\Http\Message\UploadedFileInterface
    {
        return $this->rawValue;
    }

    public function getType() : UploadType
    {
        return $this->type;
    }

    public function printValue() : string
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function prettyPrint(int $indentLevel) : string
    {
        return $this->printValue();
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        // nothing here
    }

    public function isSame(\Graphpinator\Value\Value $value) : bool
    {
        return $value instanceof self
            && $this->rawValue === $value->getRawValue();
    }
}
