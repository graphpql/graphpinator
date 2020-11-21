<?php

declare(strict_types = 1);

namespace Graphpinator\Module\Upload;

final class UploadModule implements \Graphpinator\Module\Module
{
    use \Nette\SmartObject;

    private FileProvider $fileProvider;

    public function __construct(FileProvider $fileProvider)
    {
        $this->fileProvider = $fileProvider;
    }

    public function process(\Graphpinator\ParsedRequest $request) : \Graphpinator\ParsedRequest
    {
        $variables = $request->getVariables();

        foreach ($this->fileProvider->getMap() as $fileKey => $locations) {
            $fileValue = new \Graphpinator\Value\LeafValue(
                new \Graphpinator\Module\Upload\UploadType(),
                $this->fileProvider->getFile($fileKey),
                true,
            );

            foreach ($locations as $location) {
                /**
                 * Array reverse is done so we can use array_pop (O(1)) instead of array_shift (O(n))
                 */
                $keys = \array_reverse(\explode('.', $location));

                if (\array_pop($keys) !== 'variables') {
                    throw new \Graphpinator\Exception\Upload\OnlyVariablesSupported();
                }

                $variableName = \array_pop($keys);
                $variable = $variables[$variableName];
                $variables[$variableName] = $this->insertFiles($keys, $variable, $variable->getType(), $fileValue);
            }
        }

        return $request;
    }

    private function insertFiles(
        array &$keys,
        \Graphpinator\Value\InputedValue $currentValue,
        \Graphpinator\Type\Contract\Definition $type,
        \Graphpinator\Value\LeafValue $fileValue
    ) : \Graphpinator\Value\InputedValue
    {
        if ($type instanceof \Graphpinator\Module\Upload\UploadType && $currentValue instanceof \Graphpinator\Value\NullValue) {
            if (\count($keys) === 0) {
                return $fileValue;
            }

            throw new \Graphpinator\Exception\Upload\InvalidMap();
        }

        if ($type instanceof \Graphpinator\Type\NotNullType) {
            return $this->insertFiles($keys, $currentValue, $type->getInnerType(), $fileValue);
        }

        if ($type instanceof \Graphpinator\Type\ListType) {
            $index = \array_pop($keys);

            if (!\is_numeric($index)) {
                throw new \Graphpinator\Exception\Upload\InvalidMap();
            }

            $index = (int) $index;

            if ($currentValue instanceof \Graphpinator\Value\NullValue) {
                $currentValue = new \Graphpinator\Value\ListInputedValue($type, []);
            }

            if (!isset($currentValue[$index])) {
                $currentValue[$index] = new \Graphpinator\Value\NullInputedValue($type->getInnerType());
            }

            $currentValue[$index] = $this->insertFiles($keys, $currentValue[$index], $type->getInnerType(), $fileValue);

            return $currentValue;
        }

        if ($type instanceof \Graphpinator\Type\InputType && $currentValue instanceof \Graphpinator\Value\InputValue) {
            $index = \array_pop($keys);

            if (\is_numeric($index)) {
                throw new \Graphpinator\Exception\Upload\InvalidMap();
            }

            $argument = $type->getArguments()[$index];

            $currentValue->{$index} = \Graphpinator\Argument\ArgumentValue::fromInputed(
                $argument,
                $this->insertFiles($keys, $currentValue->{$index}->getValue(), $argument->getType(), $fileValue),
            );

            return $currentValue;
        }

        throw new \Graphpinator\Exception\Upload\InvalidMap();
    }
}
