<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class Fragment
{
    use \Nette\SmartObject;

    private bool $cycleValidated = false;

    public function __construct(
        private string $name,
        private \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond,
        private \Graphpinator\Parser\Field\FieldSet $fields,
    ) {}

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\Field\FieldSet
    {
        return $this->fields;
    }

    public function getTypeCond() : \Graphpinator\Parser\TypeRef\NamedTypeRef
    {
        return $this->typeCond;
    }

    public function validateCycles(FragmentSet $fragmentDefinitions, array $stack) : void
    {
        if ($this->cycleValidated) {
            return;
        }

        if (\array_key_exists($this->name, $stack)) {
            throw new \Graphpinator\Exception\Normalizer\FragmentCycle();
        }

        $stack[$this->name] = true;
        $this->fields->validateCycles($fragmentDefinitions, $stack);
        unset($stack[$this->name]);
        $this->cycleValidated = true;
    }
}
