<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Type\Contract\Outputable $type;
    private \Graphpinator\Argument\ArgumentSet $arguments;
    protected ?string $description;

    public function __construct(string $name, \Graphpinator\Type\Contract\Outputable $type, ?\Graphpinator\Argument\ArgumentSet $arguments = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->arguments = $arguments ?? new \Graphpinator\Argument\ArgumentSet([]);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function getType() : \Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }
}
