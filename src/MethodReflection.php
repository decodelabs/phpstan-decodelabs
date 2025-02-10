<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptor;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\Type;

class MethodReflection implements MethodReflectionInterface
{
    protected ClassReflection $classReflection;
    protected string $name;
    protected bool $static = false;
    protected bool $private = false;

    /**
     * @var list<ParametersAcceptor>
     */
    protected array $variants;

    /**
     * @param list<ParametersAcceptor> $variants
     */
    public function __construct(
        ClassReflection $classReflection,
        string $name,
        array $variants
    ) {
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
    public function setStatic(
        bool $flag
    ): self {
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
    public function setPrivate(
        bool $flag
    ): self {
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


    /**
     * @param list<ParameterReflection> $params
     */
    public static function alterVariant(
        FunctionVariant $variant,
        array $params,
        ?Type $returnType = null
    ): FunctionVariant {
        return new FunctionVariant(
            TemplateTypeMap::createEmpty(),
            null,
            $params,
            $variant->isVariadic(),
            $returnType ?? $variant->getReturnType()
        );
    }

    /**
     * @param array<ParameterReflection> $params
     */
    public static function dumpParams(
        array $params
    ): void {
        $test = [];

        foreach ($params as $param) {
            $test[] = $param->getName();
        }

        if (function_exists('dd')) {
            dd($test);
        } else {
            var_dump($test);
        }
    }
}
