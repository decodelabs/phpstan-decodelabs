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
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\TrinaryLogic;

class MethodReflection implements MethodReflectionInterface
{
    protected $classReflection;
    protected $name;

    protected $static = false;
    protected $private = false;

    protected $variants;

    public function __construct(ClassReflection $classReflection, string $name, array $variants)
    {
        $this->classReflection = $classReflection;
        $this->name = $name;
        $this->variants = $variants;
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
        return $this->variants;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isFinal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getThrowType(): ?Type
    {
        return null;
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return TrinaryLogic::createMaybe();
    }
}
