<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection as PropertyReflectionInterface;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

class PropertyReflection implements PropertyReflectionInterface
{
    private ClassReflection $declaringClass;
    private Type $readableType;
    private ?Type $writableType = null;

    public function __construct(
        ClassReflection $declaringClass,
        Type $readableType,
        ?Type $writableType = null
    ) {
        $this->declaringClass = $declaringClass;
        $this->readableType = $readableType;
        $this->writableType = $writableType;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getType(): Type
    {
        if ($this->writableType) {
            return new UnionType([
                $this->readableType,
                $this->writableType
            ]);
        }

        return $this->readableType;
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function getReadableType(): Type
    {
        return $this->readableType;
    }

    public function getWritableType(): Type
    {
        return $this->writableType ?? $this->readableType;
    }

    public function canChangeTypeAfterAssignment(): bool
    {
        return false;
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }
}
