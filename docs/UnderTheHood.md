# Under the hood

So, how exactly does this library operates under the hood? 

Lets take a look at following paragraphs, which describe execution process and class responsible for each step.

***

## Parsing - understanding the request

### Tokenizer\Source

Simple class used by `Tokenizer`, which takes raw request as `string` and splits it into array of characters. \
It is able to iterate over it and also detects end of stream.

### Tokenizer\Tokenizer

Class responsible for lexical analysis. Internaly uses `Source` to move around request characters and creates `Token`s.

### Tokenizer\TokenContainer

Simple wrapper around `Tokenizer`, which preloads all tokens into array in constructor. \
By doing so, this class is able to move and peek across tokens in any direction without any additional computing cost.

### Parser\Parser

***

## Normalizing - validating and comparing parsed result against given schema

***

## Resolving - executing the request

