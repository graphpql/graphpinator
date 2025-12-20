# Documentation

Hello!

We are happy you consider using this library.

This documentation relates to the GraPHPinator project - the PHP implementation of GraphQL server.
Before you continue, make sure to understand the concepts of GraphQL and its purpose.
All the necessary information is presented on [the official website](http://graphql.org/learn/).

## Version 2.0 Breaking Changes

Version 2.0 introduces several important changes. Please review these if you are upgrading from an earlier version:

### Requirements
- **Minimum PHP version is now 8.2** (previously 8.1)

### Scalar Type Coercion
- Custom scalar types must implement new methods:
  - `validateAndCoerceInput(mixed $rawValue) : mixed` - replaces the old `validateNonNullValue` method for input validation
  - `coerceOutput(mixed $rawValue) : string|int|float|bool` - new method for output coercion
- The old `validateNonNullValue` method has been removed

### Enhanced Schema Validation
- Schema validation now checks resolver function return types against field type definitions
- NotNull fields require non-nullable return types
- List fields require iterable return types

### Namespace Changes
Some classes have been moved to different namespaces. Please check your imports if you encounter class-not-found errors after upgrading.

**For detailed migration instructions, see the [Migration Guide](migration-v2.md).**

## Compliance status

This library aims at the [latest draft of the GraphQL specification](http://spec.graphql.org/draft/).
The current version supports all the features and is ready to be used in real-world applications.

Known incompatibilities can be found in [issues with "Spec incompatibility" label](https://github.com/graphpql/graphpinator/issues?q=is%3Aopen+is%3Aissue+label%3A%22Ctg+-+Spec+incompatibility%22). Those deviations from specification should not affect your application in any way, but are listed anyway for full transparency.

## Table of contents:

### Starting out

This section includes some general information about the library and how to use it but does not go deep into unnecessary detail.

> If you prefer to read less text and more code - check out the Examples

- [Schema definition and typesystem](typesystem.md)
  - Creating types (Type, Interface, Union, Scalar, Enum, Input)
  - Schema validation and integrity checks
  - Field resolver validation
  - Performance best practices
  - Error handling
  - Advanced type system features
- Request execution

### Addons

Information about unofficial extensions this library provides.

- Addon types
- Constraint directives
- Modules
  - Upload
  - Query cost
  - Persisted queries

### Utilities

Information about internal components, which may be used as standalone tools.

- [How does this lib work internally](UnderTheHood.md)
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
