<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\ParameterReflection as ParameterReflectionInterface;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\Type;

class ParameterReflection implements ParameterReflectionInterface
{
    public function __construct(
        private string $name,
        private Type $type,
    ) {
    }

    public function getDefaultValue(): ?Type
    {
        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function isOptional(): bool
    {
        return false;
    }

    public function isVariadic(): bool
    {
        return false;
    }

    public function passedByReference(): PassedByReference
    {
        return PassedByReference::createNo();
    }
}
