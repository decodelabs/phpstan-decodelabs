<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Reflection\ParameterReflection as ParameterReflectionInterface;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\Type;
use PHPStan\Type\NullType;

class ParameterReflection implements ParameterReflectionInterface
{
    protected $name;
    protected $optional = false;
    protected $type;
    protected $passByReference;
    protected $variadic = false;

    public function __construct(string $name, Type $type, bool $optional=false, PassedByReference $passByReference=null, bool $variadic=false)
    {
        $this->name = $name;
        $this->type = $type;
        $this->optional = $optional;
        $this->passByReference = $passByReference ?? PassedByReference::createNo();
        $this->variadic = $variadic;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function passedByReference(): PassedByReference
    {
        return $this->passByReference;
    }

    public function isVariadic(): bool
    {
        return $this->variadic;
    }
}
