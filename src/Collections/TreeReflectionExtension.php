<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Collections;

use DecodeLabs\Collections\Tree;
use DecodeLabs\PHPStan\PropertyReflection;

use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection as PropertyReflectionInterface;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IterableType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;

class TreeReflectionExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(
        ClassReflection $classReflection,
        string $propertyName
    ): bool {
        $class = $classReflection->getName();

        return
            $class == Tree::class ||
            is_subclass_of($class, Tree::class);
    }

    public function getProperty(
        ClassReflection $classReflection,
        string $propertyName
    ): PropertyReflectionInterface {
        $t = $classReflection->getMethod('__set', new OutOfClassScope())->getVariants()[0]->getParameters()[0]->getType();

        return new PropertyReflection(
            $classReflection,
            new ObjectType($classReflection->getName()),
            new UnionType([
                $t,
                new IterableType(new UnionType([
                    new IntegerType(),
                    new StringType()
                ]), $t)
            ])
        );
    }
}
