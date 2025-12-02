# PHPStan DecodeLabs — Package Specification

> **Cluster:** `tooling`
> **Language:** `php`
> **Milestone:** `m1`
> **Repo:** `https://github.com/decodelabs/phpstan-decodelabs`
> **Role:** PHPStan helpers

## Overview

### Purpose

PHPStan DecodeLabs provides helper classes and utilities for creating PHPStan extensions that work with DecodeLabs libraries. It provides reflection implementations that enable PHPStan to understand dynamic method, property, and parameter definitions used throughout the DecodeLabs ecosystem.

Key features:
- **Method reflection**: Custom method reflection implementation for dynamic methods
- **Parameter reflection**: Custom parameter reflection implementation for dynamic parameters
- **Property reflection**: Custom property reflection implementation for dynamic properties with read/write type differentiation
- **Static method reflection**: Wrapper for static method reflection
- **Variant manipulation**: Utilities for altering function variants and parameters

### Non-Goals

- PHPStan DecodeLabs does not provide PHPStan itself (requires PHPStan as dependency).
- It does not register PHPStan extensions automatically (extensions registered by consuming packages).
- It does not provide analysis rules or custom rules (only reflection helpers).
- It does not provide type definitions or stubs (only reflection implementations).

## Role in the Ecosystem

### Cluster & Positioning

PHPStan DecodeLabs belongs to the **tooling** cluster, providing foundational reflection classes for PHPStan extensions used across DecodeLabs packages. It serves as a base library that other packages extend to create their own PHPStan extensions, enabling static analysis of dynamic code patterns.

### Usage Contexts

- **PHPStan extensions**: Building PHPStan extensions for DecodeLabs packages
- **Dynamic method reflection**: Reflecting dynamically defined methods
- **Dynamic property reflection**: Reflecting dynamically defined properties with read/write types
- **Dynamic parameter reflection**: Reflecting dynamically defined parameters
- **Type analysis**: Enabling PHPStan to understand dynamic type definitions

## Public Surface

### Key Types

- **`MethodReflection`** (class): Custom method reflection implementation implementing `PHPStan\Reflection\MethodReflection`. Provides method metadata and variant management.

- **`ParameterReflection`** (class): Custom parameter reflection implementation implementing `PHPStan\Reflection\ParameterReflection`. Provides parameter type information.

- **`PropertyReflection`** (class): Custom property reflection implementation implementing `PHPStan\Reflection\PropertyReflection`. Provides property type information with separate read/write types.

- **`StaticMethodReflection`** (class): Wrapper for static method reflection, implementing `PHPStan\Reflection\MethodReflection`. Wraps a `MethodReflection` and marks it as static.

### Main Entry Points

**MethodReflection:**
- `new MethodReflection(ClassReflection $classReflection, string $name, array $variants)` — Constructor
- `$reflection->getName(): string` — Get method name
- `$reflection->getDeclaringClass(): ClassReflection` — Get declaring class
- `$reflection->setStatic(bool $flag): self` — Set static flag
- `$reflection->isStatic(): bool` — Check if static
- `$reflection->setPrivate(bool $flag): self` — Set private flag
- `$reflection->isPrivate(): bool` — Check if private
- `$reflection->isPublic(): bool` — Check if public
- `$reflection->getPrototype(): ClassMemberReflection` — Get prototype
- `$reflection->getVariants(): array` — Get function variants
- `$reflection->getDocComment(): ?string` — Get doc comment
- `$reflection->isDeprecated(): TrinaryLogic` — Check if deprecated
- `$reflection->getDeprecatedDescription(): ?string` — Get deprecated description
- `$reflection->isFinal(): TrinaryLogic` — Check if final
- `$reflection->isInternal(): TrinaryLogic` — Check if internal
- `$reflection->getThrowType(): ?Type` — Get throw type
- `$reflection->hasSideEffects(): TrinaryLogic` — Check if has side effects
- `MethodReflection::alterVariant(FunctionVariant $variant, array $params, ?Type $returnType = null): FunctionVariant` — Alter function variant
- `MethodReflection::dumpParams(array $params): void` — Debug dump parameters

**ParameterReflection:**
- `new ParameterReflection(string $name, Type $type)` — Constructor
- `$reflection->getDefaultValue(): ?Type` — Get default value type
- `$reflection->getName(): string` — Get parameter name
- `$reflection->getType(): Type` — Get parameter type
- `$reflection->isOptional(): bool` — Check if optional
- `$reflection->isVariadic(): bool` — Check if variadic
- `$reflection->passedByReference(): PassedByReference` — Check if passed by reference

**PropertyReflection:**
- `new PropertyReflection(ClassReflection $declaringClass, Type $readableType, ?Type $writableType = null)` — Constructor
- `$reflection->getDeclaringClass(): ClassReflection` — Get declaring class
- `$reflection->isStatic(): bool` — Check if static
- `$reflection->isPrivate(): bool` — Check if private
- `$reflection->isPublic(): bool` — Check if public
- `$reflection->getType(): Type` — Get property type (union of readable and writable if different)
- `$reflection->isReadable(): bool` — Check if readable
- `$reflection->isWritable(): bool` — Check if writable
- `$reflection->getDocComment(): ?string` — Get doc comment
- `$reflection->getReadableType(): Type` — Get readable type
- `$reflection->getWritableType(): Type` — Get writable type
- `$reflection->canChangeTypeAfterAssignment(): bool` — Check if type can change after assignment
- `$reflection->isDeprecated(): TrinaryLogic` — Check if deprecated
- `$reflection->getDeprecatedDescription(): ?string` — Get deprecated description
- `$reflection->isInternal(): TrinaryLogic` — Check if internal

**StaticMethodReflection:**
- `new StaticMethodReflection(MethodReflection $methodReflection)` — Constructor
- `$reflection->getDeclaringClass(): ClassReflection` — Get declaring class
- `$reflection->isStatic(): bool` — Always returns `true`
- `$reflection->isPrivate(): bool` — Check if private
- `$reflection->isPublic(): bool` — Check if public
- `$reflection->getDocComment(): ?string` — Get doc comment
- `$reflection->getName(): string` — Get method name
- `$reflection->getPrototype(): ClassMemberReflection` — Get prototype
- `$reflection->getVariants(): array` — Get function variants
- `$reflection->isDeprecated(): TrinaryLogic` — Check if deprecated
- `$reflection->getDeprecatedDescription(): ?string` — Get deprecated description
- `$reflection->isFinal(): TrinaryLogic` — Check if final
- `$reflection->isInternal(): TrinaryLogic` — Check if internal
- `$reflection->getThrowType(): ?Type` — Get throw type
- `$reflection->hasSideEffects(): TrinaryLogic` — Check if has side effects

## Dependencies

### Decode Labs

None.

### External

- **PHP**: See `composer.json` for supported PHP versions.
- **PHPStan**: Required dependency (^2.1.4) — PHPStan static analysis tool.
- **phpstan/extension-installer**: Required dependency (^1.4.3) — PHPStan extension installer.

## Behaviour & Contracts

### Invariants

- Reflection classes implement PHPStan reflection interfaces.
- Method reflection supports multiple function variants.
- Property reflection supports separate read/write types.
- Static method reflection always returns `true` for `isStatic()`.
- Parameter reflection defaults to required, non-variadic, not passed by reference.
- Property reflection defaults to public, non-static, readable, writable.

### Input & Output Contracts

**Method Reflection:**
- Constructor accepts class reflection, method name, and array of function variants.
- Variants must implement `ParametersAcceptor` interface.
- Static flag can be set via `setStatic()`.
- Private flag can be set via `setPrivate()`.
- Public status derived from private flag (not private = public).
- Variants returned as array of `ParametersAcceptor` instances.
- Deprecated, final, internal, throw type, side effects return default values (no/false/null).

**Parameter Reflection:**
- Constructor accepts parameter name and type.
- Default value always returns `null`.
- Optional always returns `false`.
- Variadic always returns `false`.
- Passed by reference always returns `PassedByReference::createNo()`.

**Property Reflection:**
- Constructor accepts declaring class, readable type, and optional writable type.
- If writable type provided and different from readable type, `getType()` returns union type.
- If writable type not provided, `getType()` returns readable type.
- Static always returns `false`.
- Private always returns `false`.
- Public always returns `true`.
- Readable always returns `true`.
- Writable always returns `true`.
- Type change after assignment always returns `false`.

**Static Method Reflection:**
- Constructor accepts `MethodReflection` instance.
- Wraps method reflection and delegates most calls.
- `isStatic()` always returns `true`.
- Other methods delegate to wrapped method reflection.

**Variant Manipulation:**
- `alterVariant()` creates new `FunctionVariant` with modified parameters and optional return type.
- Original variant's variadic flag preserved.
- Template type map set to empty.
- Accepts null return type (uses original if not provided).

**Parameter Debugging:**
- `dumpParams()` dumps parameter names for debugging.
- Uses `dd()` if available (Laravel), otherwise `var_dump()`.

## Error Handling

- **Invalid variant types**: PHPStan type system enforces `ParametersAcceptor` interface.
- **Invalid type definitions**: PHPStan type system validates type instances.
- **Missing dependencies**: Composer handles dependency resolution.

## Configuration & Extensibility

### PHPStan Configuration

PHPStan extensions using these classes register via `phpstan-extension.neon`:

```neon
services:
    -
        class: DecodeLabs\PHPStan\YourReflectionExtension
        tags:
            - phpstan.broker.methodsClassReflectionExtension
            - phpstan.broker.propertiesClassReflectionExtension
```

### Extending Reflection Classes

Reflection classes can be extended or used directly:

```php
use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\ParameterReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\StringType;

$method = new MethodReflection(
    $classReflection,
    'methodName',
    [
        new FunctionVariant(
            TemplateTypeMap::createEmpty(),
            null,
            [
                new ParameterReflection('param', new StringType())
            ],
            false,
            new StringType()
        )
    ]
);
```

### Property Reflection with Read/Write Types

```php
use DecodeLabs\PHPStan\PropertyReflection;
use PHPStan\Type\StringType;
use PHPStan\Type\IntegerType;

$property = new PropertyReflection(
    $classReflection,
    new StringType(), // Readable type
    new IntegerType() // Writable type (optional)
);
```

## Interactions with Other Packages

- **PHPStan**: Provides reflection interfaces and type system.
- **DecodeLabs packages**: Consume reflection classes to create PHPStan extensions (e.g., `veneer`, `tagged`, `metamorph`, `terminus`).

## Usage Examples

### Creating Method Reflection

```php
use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\ParameterReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\StringType;
use PHPStan\Type\IntegerType;

$method = new MethodReflection(
    $classReflection,
    'process',
    [
        new FunctionVariant(
            TemplateTypeMap::createEmpty(),
            null,
            [
                new ParameterReflection('input', new StringType())
            ],
            false,
            new IntegerType()
        )
    ]
);

$method->setStatic(false);
$method->setPrivate(false);
```

### Creating Property Reflection

```php
use DecodeLabs\PHPStan\PropertyReflection;
use PHPStan\Type\StringType;
use PHPStan\Type\IntegerType;

// Read-only property
$readOnly = new PropertyReflection(
    $classReflection,
    new StringType()
);

// Read/write property with different types
$readWrite = new PropertyReflection(
    $classReflection,
    new StringType(), // Read type
    new IntegerType() // Write type
);
```

### Creating Static Method Reflection

```php
use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\StaticMethodReflection;

$method = new MethodReflection(/* ... */);
$staticMethod = new StaticMethodReflection($method);
// $staticMethod->isStatic() always returns true
```

### Altering Function Variant

```php
use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\ParameterReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\StringType;

$variant = new FunctionVariant(/* ... */);
$altered = MethodReflection::alterVariant(
    $variant,
    [
        new ParameterReflection('newParam', new StringType())
    ],
    new StringType() // New return type
);
```

### Debugging Parameters

```php
use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\ParameterReflection;

$params = [
    new ParameterReflection('param1', new StringType()),
    new ParameterReflection('param2', new IntegerType())
];

MethodReflection::dumpParams($params);
// Outputs: ['param1', 'param2']
```

## Implementation Notes (for Contributors)

### Reflection Implementation

- Reflection classes implement PHPStan reflection interfaces.
- Method reflection supports multiple variants for method overloading.
- Property reflection supports separate read/write types for properties with different access types.
- Static method reflection wraps method reflection and marks as static.

### Method Reflection

- Stores class reflection, method name, and function variants.
- Supports static and private flags (settable).
- Variants stored as array of `ParametersAcceptor` instances.
- Default values for deprecated, final, internal, throw type, side effects.

### Parameter Reflection

- Stores parameter name and type.
- Defaults to required, non-variadic, not passed by reference.
- No default value support (always returns `null`).

### Property Reflection

- Stores declaring class, readable type, and optional writable type.
- If writable type provided and different, `getType()` returns union type.
- Defaults to public, non-static, readable, writable.
- Type change after assignment always returns `false`.

### Static Method Reflection

- Wraps `MethodReflection` instance.
- Delegates most method calls to wrapped reflection.
- `isStatic()` always returns `true`.
- Other methods delegate to wrapped reflection.

### Variant Manipulation

- `alterVariant()` creates new `FunctionVariant` with modified parameters.
- Original variant's variadic flag preserved.
- Template type map set to empty.
- Return type can be overridden or preserved.

### Parameter Debugging

- `dumpParams()` extracts parameter names and dumps them.
- Uses `dd()` if available (Laravel), otherwise `var_dump()`.
- Useful for debugging parameter extraction in extensions.

## Testing & Quality

**Current Status:**
- Code quality: 3/5
- README quality: 3/5
- Documentation: 0/5 (no formal docs yet)
- Tests: 0/5 (no test suite yet)

**Testing Considerations:**
- Reflection classes should be tested for:
  - Interface implementation (PHPStan reflection interfaces)
  - Method reflection (variants, static, private flags)
  - Parameter reflection (name, type, optional, variadic)
  - Property reflection (read/write types, union types)
  - Static method reflection (wrapping, static flag)
  - Variant manipulation (parameter modification, return type)
  - Parameter debugging (name extraction, output)

- Method reflection should be tested for:
  - Variant storage and retrieval
  - Static flag setting and retrieval
  - Private flag setting and retrieval
  - Public status derivation
  - Default values (deprecated, final, internal, throw type, side effects)

- Parameter reflection should be tested for:
  - Name and type storage
  - Default value (always null)
  - Optional status (always false)
  - Variadic status (always false)
  - Passed by reference (always no)

- Property reflection should be tested for:
  - Readable type storage
  - Writable type storage (optional)
  - Union type generation (when read/write types differ)
  - Readable type when writable not provided
  - Default values (static, private, public, readable, writable)

- Static method reflection should be tested for:
  - Wrapping method reflection
  - Static flag (always true)
  - Method delegation
  - All interface methods

- Variant manipulation should be tested for:
  - Parameter modification
  - Return type modification
  - Variadic flag preservation
  - Template type map handling

## Roadmap & Future Ideas

- **Enhanced type support**: Better support for complex type definitions
- **Default value support**: Support for parameter default values
- **Variadic parameter support**: Support for variadic parameters
- **Reference parameter support**: Support for parameters passed by reference
- **Documentation**: Enhanced documentation and examples
- **Testing**: Comprehensive test suite
- **Performance**: Performance optimization for large codebases

## References

- Package repository: https://github.com/decodelabs/phpstan-decodelabs
- Composer package: https://packagist.org/packages/decodelabs/phpstan-decodelabs
- PHPStan documentation: https://phpstan.org/
- Related packages:
  - PHPStan: Static analysis tool
  - DecodeLabs packages: Consume reflection classes for PHPStan extensions

