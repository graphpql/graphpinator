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

    public function processRequest(\Graphpinator\Request\Request $request) : \Graphpinator\Request\Request
    {
        $variables = $request->getVariables();

        foreach ($this->fileProvider->getMap() as $fileKey => $locations) {
            $fileValue = $this->fileProvider->getFile($fileKey);

            foreach ($locations as $location) {
                /**
                 * Array reverse is done so we can use array_pop (O(1)) instead of array_shift (O(n))
                 */
                $keys = \array_reverse(\explode('.', $location));

                if (\array_pop($keys) !== 'variables') {
                    throw new \Graphpinator\Exception\Upload\OnlyVariablesSupported();
                }

                $variableName = \array_pop($keys);

                if (!\property_exists($variables, $variableName)) {
                    throw new \Graphpinator\Exception\Upload\InvalidMap();
                }

                $variable = $variables->{$variableName};
                $variables->{$variableName} = $this->insertFiles($keys, $variable, $fileValue);
            }
        }

        return $request;
    }

    public function processParsed(\Graphpinator\Parser\ParsedRequest $request) : \Graphpinator\Parser\ParsedRequest
    {
        return $request;
    }

    public function processNormalized(\Graphpinator\Normalizer\NormalizedRequest $request) : \Graphpinator\Normalizer\NormalizedRequest
    {
        return $request;
    }

    public function processFinalized(\Graphpinator\OperationRequest $request) : \Graphpinator\OperationRequest
    {
        return $request;
    }

    private function insertFiles(
        array &$keys,
        array|\stdClass|null $currentValue,
        \Psr\Http\Message\UploadedFileInterface $fileValue,
    ) : array|\stdClass|\Psr\Http\Message\UploadedFileInterface
    {
        if (\count($keys) === 0) {
            if ($currentValue === null) {
                return $fileValue;
            }

            throw new \Graphpinator\Exception\Upload\InvalidMap();
        }

        $index = \array_pop($keys);

        if (\is_numeric($index)) {
            $index = (int) $index;

            if ($currentValue === null) {
                $currentValue = [];
            }

            if (\is_array($currentValue)) {
                if (!\array_key_exists($index, $currentValue)) {
                    $currentValue[$index] = null;
                }

                $currentValue[$index] = $this->insertFiles($keys, $currentValue[$index], $fileValue);

                return $currentValue;
            }

            throw new \Graphpinator\Exception\Upload\InvalidMap();
        }

        if (!$currentValue instanceof \stdClass) {
            throw new \Graphpinator\Exception\Upload\InvalidMap();
        }

        if (!\property_exists($currentValue, $index)) {
            $currentValue->{$index} = null;
        }

        $currentValue->{$index} = $this->insertFiles($keys, $currentValue->{$index}, $fileValue);

        return $currentValue;
    }
}
