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
            );

            foreach ($locations as $location) {
                /**
                 * Array reverse is done so we can use array_pop (O(1)) instead of array_shift (O(n))
                 */
                $keys = \array_reverse(\explode('.', $location));

                if (\array_pop($keys) !== 'variables') {
                    throw new \Nette\NotSupportedException;
                }

                $currentKey = \array_pop($keys);
                $currentValue = &$variables[$currentKey];
                $currentType = $currentValue->getType();

                while (true) {
                    if ($currentType instanceof \Graphpinator\Module\Upload\UploadType &&
                        $currentValue instanceof \Graphpinator\Value\NullValue) {
                        if (!empty($keys)) {
                            throw new \Nette\NotSupportedException();
                        }

                        $currentValue = $fileValue;

                        break;
                    }

                    if ($currentType instanceof \Graphpinator\Type\NotNullType) {
                        $currentType = $currentType->getInnerType();

                        continue;
                    }

                    if ($currentType instanceof \Graphpinator\Type\ListType &&
                        $currentValue instanceof \Graphpinator\Value\ListInputedValue) {
                        $index = \array_pop($keys);

                        if (!\is_numeric($index)) {
                            throw new \Nette\NotSupportedException();
                        }

                        $currentType = $currentType->getInnerType();
                        $currentValue = &$currentValue[(int) $index];

                        continue;
                    }

                    if ($currentType instanceof \Graphpinator\Type\InputType &&
                        $currentValue instanceof \Graphpinator\Value\InputValue) {
                        $index = \array_pop($keys);

                        if (\is_numeric($index)) {
                            throw new \Nette\NotSupportedException();
                        }

                        $currentType = $currentType->getArguments()[$index];
                        $currentValue = &$currentValue->{$index};

                        continue;
                    }

                    throw new \Nette\NotSupportedException();
                }
            }
        }

        return $request;
    }
}
