# Migration Guide: Version 1.x to 2.0

This guide helps you migrate your GraPHPinator application from version 1.x to version 2.0.

## System Requirements

### PHP Version

**Action Required**: Upgrade to PHP 8.2 or higher.

```bash
# Check your PHP version
php -v
```

Version 2.0 requires PHP 8.2+, while version 1.x required PHP 8.1+.

## Breaking Changes

### 1. Scalar Type Implementation

The most significant change is in how custom scalar types are implemented.

#### Old Implementation (v1.x)

```php
final class EmailAddressType extends ScalarType
{
    protected const NAME = 'EmailAddress';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_EMAIL);
    }
}
```

#### New Implementation (v2.0)

```php
final class EmailAddressType extends ScalarType
{
    protected const NAME = 'EmailAddress';

    public function validateAndCoerceInput(mixed $rawValue) : mixed
    {
        if (!\is_string($rawValue)) {
            return null; // Invalid value
        }

        if (!(bool) \filter_var($rawValue, \FILTER_VALIDATE_EMAIL)) {
            return null; // Invalid value
        }

        return $rawValue; // Valid and coerced value
    }

    public function coerceOutput(mixed $rawValue) : string|int|float|bool
    {
        return $rawValue; // Coerce to JSON-serializable type
    }
}
```

#### Migration Steps

1. Remove the `validateNonNullValue()` method
2. Add `validateAndCoerceInput()` method:
   - Return `null` for invalid values (instead of returning `false`)
   - Return the coerced value for valid values
   - This method handles input from GraphQL queries and variables
3. Add `coerceOutput()` method:
   - Transform your internal PHP representation to a JSON-serializable type
   - Return `string`, `int`, `float`, or `bool`
   - This method handles output values in GraphQL responses

#### Common Patterns

**Simple validation without coercion:**
```php
public function validateAndCoerceInput(mixed $rawValue) : mixed
{
    if (/* validation check */) {
        return $rawValue; // Return as-is if valid
    }

    return null; // Invalid
}

public function coerceOutput(mixed $rawValue) : string|int|float|bool
{
    return $rawValue; // Return as-is if already serializable
}
```

**With input coercion (string normalization):**
```php
public function validateAndCoerceInput(mixed $rawValue) : mixed
{
    if (!\is_string($rawValue) && !\is_int($rawValue)) {
        return null; // Accept both string and int
    }

    return (string) $rawValue; // Always return as string
}

public function coerceOutput(mixed $rawValue) : string
{
    return (string) $rawValue;
}
```

**Advanced: Converting to PHP objects**

One of the most powerful features in v2.0 is the ability to convert scalar values into PHP objects:

```php
public function validateAndCoerceInput(mixed $rawValue) : ?\DateTimeImmutable
{
    if (!\is_string($rawValue)) {
        return null;
    }

    try {
        return new \DateTimeImmutable($rawValue); // Convert to object
    } catch (\Exception $e) {
        return null; // Invalid date string
    }
}

public function coerceOutput(mixed $rawValue) : string
{
    \assert($rawValue instanceof \DateTimeImmutable);

    return $rawValue->format(\DateTimeInterface::ATOM); // Convert back to string
}
```

Now your resolvers can work directly with `\DateTimeImmutable` objects instead of strings! See the [typesystem documentation](typesystem.md#working-with-objects---advanced-scalar-coercion) for more details.

> **Tip**: The [graphpinator-extra-types](https://github.com/graphpql/graphpinator-extra-types) package includes many ready-to-use scalar types with object coercion (DateTime, Date, Email, UUID, etc.).

### 2. Field Resolver Type Hints

Version 2.0 validates that resolver function return types match field type declarations.

#### Impact

If your resolvers have incorrect type hints, schema initialization will fail with exceptions:

- `FieldResolverNullabilityMismatch` - Return type nullability doesn't match field
- `FieldResolverNotIterable` - List field doesn't return iterable
- `FieldResolverVoidReturnType` - Resolver returns void

#### Migration Steps

1. Review all your resolver functions
2. Add or fix return type declarations to match field types:

```php
// ❌ INCORRECT - field is notNull but return type allows null
ResolvableField::create(
    'name',
    Container::String()->notNull(),
    function (UserDto $user) : ?string {
        return $user->name;
    },
)

// ✅ CORRECT - return type matches field
ResolvableField::create(
    'name',
    Container::String()->notNull(),
    function (UserDto $user) : string {
        return $user->name;
    },
)

// ❌ INCORRECT - list field without iterable return
ResolvableField::create(
    'friends',
    $friendType->list(),
    function (UserDto $user) : \Generator {
        foreach ($user->friends as $friend) {
            yield $friend;
        }
    },
)

// ✅ CORRECT - list field with iterable return
ResolvableField::create(
    'friends',
    $friendType->list(),
    function (UserDto $user) : iterable {
        foreach ($user->friends as $friend) {
            yield $friend;
        }
    },
)
```

3. If you can't add return types, remove any existing incorrect hints - validation is skipped when no return type is present

### 3. Namespace Changes

Some classes have been moved to different namespaces.

#### Migration Steps

1. Run your application and watch for "Class not found" errors
2. Update imports based on error messages
3. Common changes include:
   - Some internal visitor classes
   - Some exception classes

If you're using IDE auto-import features, this should be handled automatically.

## Testing Your Migration

### 1. Update Dependencies

```bash
composer update infinityloop-dev/graphpinator
```

### 2. Run Your Test Suite

```bash
vendor/bin/phpunit
```

### 3. Check Schema Initialization

Try to instantiate your schema in a test:

```php
public function testSchemaInitialization() : void
{
    $schema = new YourSchema($container);
    $this->assertInstanceOf(Schema::class, $schema);
}
```

This will catch most schema validation errors.

### 4. Test Scalar Types

For each custom scalar type:

```php
public function testEmailScalarInput() : void
{
    $scalar = new EmailAddressType();

    // Test valid input
    $this->assertEquals('test@example.com', $scalar->validateAndCoerceInput('test@example.com'));

    // Test invalid input
    $this->assertNull($scalar->validateAndCoerceInput('invalid'));
    $this->assertNull($scalar->validateAndCoerceInput(123));
}

public function testEmailScalarOutput() : void
{
    $scalar = new EmailAddressType();

    $this->assertEquals('test@example.com', $scalar->coerceOutput('test@example.com'));
}
```

## Benefits of Version 2.0

After migration, you'll benefit from:

1. **Better type safety**: Resolver validation catches mismatches at schema initialization
2. **Clearer scalar semantics**: Separate input/output coercion methods
3. **Modern PHP features**: Takes advantage of PHP 8.2 features
4. **Improved error messages**: More specific exceptions help debug issues faster

## Need Help?

If you encounter issues during migration:

1. Check the [full documentation](README.md)
2. Review the [examples](examples/)
3. [Open an issue](https://github.com/graphpql/graphpinator/issues) on GitHub
