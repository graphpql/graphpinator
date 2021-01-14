<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidValueType extends \Graphpinator\Exception\Directive\BaseWhereException
{
    public const MESSAGE = '%s directive expects filtered value to be %s, got %s.';

    public function __construct(string $directiveName, string $expected, \Graphpinator\Value\ResolvedValue $resolvedValue)
    {
        $this->messageArgs = [
            \Infinityloop\Utils\CaseConverter::toPascalCase($directiveName),
            $expected,
            $this->printType($resolvedValue),
        ];

        parent::__construct();
    }
}
