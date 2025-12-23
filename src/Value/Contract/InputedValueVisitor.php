<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Contract;

use Graphpinator\Value\EnumValue;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\VariableValue;

/**
 * @template T
 */
interface InputedValueVisitor
{
    /**
     * @return T
     */
    public function visitNull(NullValue $nullValue) : mixed;

    /**
     * @return T
     */
    public function visitList(ListValue $listValue) : mixed;

    /**
     * @return T
     */
    public function visitScalar(ScalarValue $scalarValue) : mixed;

    /**
     * @return T
     */
    public function visitEnum(EnumValue $enumValue) : mixed;

    /**
     * @return T
     */
    public function visitInput(InputValue $inputValue) : mixed;

    /**
     * @return T
     */
    public function visitVariable(VariableValue $variableValue) : mixed;
}
