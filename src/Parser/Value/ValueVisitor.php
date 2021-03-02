<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

interface ValueVisitor
{
    public function visitLiteral(Literal $literal) : mixed;

    public function visitEnumLiteral(EnumLiteral $enumLiteral) : mixed;

    public function visitListVal(ListVal $listVal) : mixed;

    public function visitObjectVal(ObjectVal $objectVal) : mixed;

    public function visitVariableRef(VariableRef $variableRef) : mixed;
}
