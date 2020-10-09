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
        foreach ($this->fileProvider->getMap() as $fileKey => $locations) {
            $fileValue = new \Graphpinator\Value\LeafValue(
                new \Graphpinator\Type\Addon\UploadType(),
                $this->fileProvider->getFile($fileKey),
            );

            foreach ($locations as $location) {
                $keys = \explode('.', $location);

                if (\array_shift($keys) !== 'variables') {
                    throw new \Nette\NotSupportedException;
                }

                $variable = $request->getVariables()[\array_shift($keys)];

                $level = 0;
                $lastLevel = \count($keys);

                foreach ($keys as $key) {
                    ++$level;

                    if ($level === $lastLevel) {
                        if (\is_numeric($key) &&
                            $variable[$key] instanceof \Graphpinator\Value\ListValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }

                        if (\is_string($key) &&
                            $variable[$key] instanceof \Graphpinator\Value\InputValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }
                    } else {
                        if (\is_numeric($key) &&
                            $variable[$key] instanceof \Graphpinator\Value\ListValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }

                        if (\is_string($key) &&
                            $variable[$key] instanceof \Graphpinator\Value\InputValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }
                    }

                    throw new \Nette\NotSupportedException();
                }

            }
        }
    }

    private function setFile(
        \Graphpinator\Value\LeafValue $file,
        \Graphpinator\Resolver\Value\ValidatedValue $value,
        array $location
    ) : void
    {
        $key = \array_shift($location);

        if ($key)

        if (\is_numeric($key) &&
            $value instanceof \Graphpinator\Resolver\Value\ListValue) {
            $variable[(int) $key] = $fileValue;
        }

        if (\is_string($key) &&
            $value instanceof \Graphpinator\Resolver\Value\InputValue) {
            $variable[$key] = $fileValue;
        }
    }
}
