<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

class Field
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Utils\TDeprecatable;

    protected string $name;
    protected \Graphpinator\Type\Contract\Outputable $type;
    protected \Graphpinator\Argument\ArgumentSet $arguments;

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

    public function getType() : \Graphpinator\Type\Contract\Outputable
    {
        return $this->type;
    }

    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->arguments;
    }
}
