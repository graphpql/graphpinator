# Documentation

Hello! 

We are happy you consider using this library.

This documentation relates to the GraPHPinator project - the PHP implementation of GraphQL server. 
Before you continue, make sure to understand the concepts of GraphQL and its purpose.
All the neccasarry information are clearly presented on [the official website](http://graphql.org/learn/).

## Compliance status

This library aims at the [latest draft of the GraphQL specification](http://spec.graphql.org/draft/).

The current version supports all the features and is ready to be used in real-world applications.

Known incompatibilities can be found in [issues with "Spec incompatibility" label](https://github.com/infinityloop-dev/graphpinator/issues?q=is%3Aopen+is%3Aissue+label%3A%22Ctg+-+Spec+incompatibility%22).

## Table of contents:

### Starting out

This section includes some general information about the library and how to use it, but does not go deep into unnecessary detail.

> If you prefer to read less text and more code - check out the Examples

- [Schema definition](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/DefiningSchema.md)
- Request execution
- [How does this lib work internally](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/UnderTheHood.md)

### Type system

This section includes detailed information about how this library represents GraphQL typesystem.

#### GraphQL type abstractions:

- Type
- Union
- Interface
- Enum
- Input
- List & NotNull
- Directive
- Schema

#### Other abstractions:

- Field
- Argument
- Type Container

#### Addons

Information about unofficial extensions this library provides.

- Addon types
- Constraint directives
- Modules
  - Upload

### Utilities

Information about internal components, which may be used as standalone tools.

- Tokenizer
- Parser

## Examples:

1. [Hello World](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples/HelloWorld.md)
2. [Union](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples/SimpleUnion.md)
3. [Interface](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples/SimpleInterface.md)
4. Enum
5. Argument
6. Input
7. Deprecated
8. Constraint
9. Addon types
10. Upload
