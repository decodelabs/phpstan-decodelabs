<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Collections;

use DecodeLabs\PHPStan\PropertyReflection;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection as PropertyReflectionInterface;
use PHPStan\Type\ObjectType;

class TreeReflectionExtension implements PropertiesClassReflectionExtension
{
    public function hasProperty(
        ClassReflection $classReflection,
        string $propertyName
    ): bool {
        $class = $classReflection->getName();
        return is_subclass_of($class, 'DecodeLabs\\Collections\\Tree');
    }

    public function getProperty(
        ClassReflection $classReflection,
        string $propertyName
    ): PropertyReflectionInterface {
        return new PropertyReflection($classReflection, new ObjectType($classReflection->getName()));
    }
}
