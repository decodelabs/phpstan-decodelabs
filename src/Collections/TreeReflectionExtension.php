<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan\Collections;

use DecodeLabs\PHPStan\PropertyReflection;
use DecodeLabs\Collections\Tree;

use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection as PropertyReflectionInterface;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ObjectType;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;

class TreeReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }


    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        $class = $classReflection->getName();
        return is_subclass_of($class, Tree::class);
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflectionInterface
    {
        return new PropertyReflection($classReflection, new ObjectType($classReflection->getName()));
    }
}
