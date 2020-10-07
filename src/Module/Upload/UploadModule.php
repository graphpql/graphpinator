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

    public function process(\Graphpinator\Request $request) : \Graphpinator\Request
    {
        foreach ($this->fileProvider->getMap() as $fileKey => $locations) {
            $fileValue = \Graphpinator\Resolver\Value\LeafValue::create(
                $this->fileProvider->getFile($fileKey),
                new \Graphpinator\Type\Addon\UploadType(),
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
                            $variable[$key] instanceof \Graphpinator\Resolver\Value\ListValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }

                        if (\is_string($key) &&
                            $variable[$key] instanceof \Graphpinator\Resolver\Value\InputValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }
                    } else {
                        if (\is_numeric($key) &&
                            $variable[$key] instanceof \Graphpinator\Resolver\Value\ListValue) {
                            $variable[$key] = $fileValue;

                            break;
                        }

                        if (\is_string($key) &&
                            $variable[$key] instanceof \Graphpinator\Resolver\Value\InputValue) {
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
        \Graphpinator\Resolver\Value\LeafValue $file,
        \Graphpinator\Resolver\Value\ValidatedValue $value,
        array $location
    ) : void
    {
        $key = \array_shift($location);

        if ($key)

        if (\is_numeric($key) &&
            $value instanceof \Graphpinator\Resolver\Value\ListValue) {
            $variable[(int) $key] = $fileValue;

            break;
        }

        if (\is_string($key) &&
            $value instanceof \Graphpinator\Resolver\Value\InputValue) {
            $variable[$key] = $fileValue;

            break;
        }
    }
}
