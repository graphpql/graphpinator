<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Feature;

final class InputObject
{
    public string $name;
    public array $number;
    public ?bool $bool;
    public ?InputObject2 $simpleInput2;
}
