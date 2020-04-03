# Under the hood

So, how exactly does this library operate under the hood? 

Lets take a look at following paragraphs, which describe execution process and class responsible for each step.

***

## Parsing - understanding the request

Parsing stage is responsible for understanding the request and validation of GraphQL syntax, without any knowledge about Schema itself. Result of parsing is object representing the request in a compact way.

### Tokenizer\Source

Simple class which takes raw request as `string` and splits it into array of characters. \
It is able to iterate over it and detect end of stream.

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

Normalizing stage is responsible for converting `Parser\ParseResult` into `Request\Operation`, which is a fully validated object that can be executed and resolved in next stage.

Converting `ParseResult` is operation that consist of few sub-operations:
  - Explode fragment spreads into fields.
  - Replace Type references (in fragment Type conditions) with Types from schema.
  - Validate Variable values and types.
  - Replace Variable references with values.
  - Validate that requested fields exist.
  - Validate that given arguments exists and that none of required is missing. 
  - Validate argument values and types.

***

## Resolving - executing the request

