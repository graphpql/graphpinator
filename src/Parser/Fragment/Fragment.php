<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Fragment;

final class Fragment
{
    use \Nette\SmartObject;

    private string $name;
    private \Graphpinator\Parser\FieldSet $fields;
    private \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond;
    private bool $cycleValidated = false;

    public function __construct(string $name, \Graphpinator\Parser\TypeRef\NamedTypeRef $typeCond, \Graphpinator\Parser\FieldSet $fields)
    {
        $this->name = $name;
        $this->typeCond = $typeCond;
        $this->fields = $fields;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : \Graphpinator\Parser\FieldSet
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
            throw new \Exception('Fragment cycle detected');
        }

        $stack[$this->name] = true;
        $this->fields->validateCycles($fragmentDefinitions, $stack);
        unset($stack[$this->name]);
        $this->cycleValidated = true;
    }
}
