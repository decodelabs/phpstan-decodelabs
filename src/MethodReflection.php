<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;

class MethodReflection implements MethodReflectionInterface
{
    /**
     * @var ClassReflection
     */
    protected $classReflection;

    /**
     * @var string
     */
    protected $name;


    /**
     * @var bool
     */
    protected $static = false;

    /**
     * @var bool
     */
    protected $private = false;


    /**
     * @var array<mixed>
     */
    protected $variants;

    /**
     * @param array<mixed> $variants
     */
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


    /**
     * @return $this
     */
    public function setStatic(bool $flag): self
    {
        $this->static = $flag;
        return $this;
    }

    public function isStatic(): bool
    {
        return $this->static;
    }

    /**
    * @return $this
    */
    public function setPrivate(bool $flag): self
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
