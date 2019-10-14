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
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;

class MethodReflection implements MethodReflectionInterface
{
    protected $classReflection;
    protected $name;

    protected $static = false;
    protected $private = false;

    public function __construct(ClassReflection $classReflection, string $name)
    {
        $this->classReflection = $classReflection;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->classReflection;
    }


    public function setStatic(bool $flag)
    {
        $this->static = $flag;
        return $this;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    public function setPrivate(bool $flag)
    {
        $this->private = $flag;
        return $this;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function isPublic(): bool
    {
        return !$this->private;
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    public function getVariants(): array
    {
        return [
            new FunctionVariant([
                new ParameterReflection('message', TypeCombinator::addNull(new StringType()), true),
                new ParameterReflection('params', TypeCombinator::addNull(new ArrayType(
                    new StringType(), new MixedType()
                )) , true),
                new ParameterReflection('data', TypeCombinator::addNull(new MixedType()), true),
            ], false, new ObjectType('Exception')),
        ];
    }
}
