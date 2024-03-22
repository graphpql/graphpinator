# Documentation

Hello! 

We are happy you consider using this library.

This documentation relates to the GraPHPinator project - the PHP implementation of GraphQL server. 
Before you continue, make sure to understand the concepts of GraphQL and its purpose.
All the necessary information is presented on [the official website](http://graphql.org/learn/).

## Compliance status

This library aims at the [latest draft of the GraphQL specification](http://spec.graphql.org/draft/).
The current version supports all the features and is ready to be used in real-world applications.

Known incompatibilities can be found in [issues with "Spec incompatibility" label](https://github.com/graphpql/graphpinator/issues?q=is%3Aopen+is%3Aissue+label%3A%22Ctg+-+Spec+incompatibility%22). Those deviations from specification should not affect your application in any way, but are listed anyway for full transparency.

## Table of contents:

### Starting out

This section includes some general information about the library and how to use it but does not go deep into unnecessary detail.

> If you prefer to read less text and more code - check out the Examples

- [Schema definition and typesystem](typesystem.md)
- Request execution
- [How does this lib work internally](UnderTheHood.md)

### Addons

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

1. [Hello World](examples/HelloWorld.md)
2. [Union](examples/Union.md)
3. [Interface](examples/Interface.md)
4. [Enum](examples/Enum.md)
5. [Arguments](examples/Arguments.md)
6. [Input](examples/Input.md)
7. [Deprecated](examples/Deprecated.md)
8. [Constraints](examples/Constraints.md)
9. Addon types
10. Upload
