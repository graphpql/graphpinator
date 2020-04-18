# Under the hood

So, how exactly does this library operate under the hood? 

Lets take a look at following paragraphs, which describe execution process and class responsible for each step.

***

## Parsing - understanding the request

Parsing stage is responsible for understanding the request and validation of GraphQL syntax, without any knowledge about Schema itself. Result of parsing is object representing the request in a structured and easily traversable way.

### Source\Source

Interface for character container. It is able to iterate over characters and detect end of stream.

#### Source\StringSource

Simple implementation which takes raw request as `string` and splits it into array of characters.

### Tokenizer\Tokenizer

Class responsible for lexical analysis. Internaly uses `Source` to move around characters and creates `Token`s.
`Token` is essentialy a group of characters which belong together from syntactic point of view - for example Integer literal is group of digits. Each `Token` has a type (which are defined in `Tokenizer\TokenType` class) and some `Token`s can also contain a value.

Very first validation is done at this point - syntax of value literals, allowed punctators, spacing between tokens, ...

### Tokenizer\TokenContainer

Simple wrapper around `Tokenizer`, which preloads all tokens into array in constructor. \
By doing so, this class is able to move and peek across tokens in any direction without any additional computing cost.

### Parser\Parser

Class responsible for syntactic analysis. Internaly uses `TokenContainer` to move around `Token`s.
By traversing `Token`s it fully validates syntax of request and constructs `ParseResult` object. `ParseResult` consists of 
compact objects representing request's operations and fragments.

***

## Normalizing - validating and comparing request against given schema

Normalizing stage is basicaly putting together ParseResult and Schema. It is responsible for converting `Parser\ParseResult` into `Request\Operation`, which is a fully validated object that can be executed and resolved in next stage.

Converting `ParseResult` is operation that consist of few sub-operations:
  - Replace Type references (string representation of Type) with instances of Types from Schema.
  - Validate Variable default values and Types.

Result of normalization is a new object, which does not modify the result from parsing. `ParseResult` can therefore be used again and normalized against different schema without parsing the string again. This is barely useful feature (how many times are you going to use the same request for multiple schemas), but it is done in order to keep the interface uniformed - execution also doesnt modify normalization result in order to possibly use it again with different variables, which is more common scenario.

***

## Resolving - executing the request

Resolving is the final step towards getting the data client asked for. At first, it takes Json of variables and replaces variable references with literal values. Then resolves request using tree structure of types and fields.

Result of resolving is `ExecutionResult`. Previous stage result remained intact, in order to use it again with different set of variables.
