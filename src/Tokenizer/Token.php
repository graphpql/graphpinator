<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class Token
{
    use \Nette\SmartObject;

    private string $type;
    private ?string $value;

    public function __construct(string $type, ?string $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getValue() : ?string
    {
        return $this->value;
    }
}
