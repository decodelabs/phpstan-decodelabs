<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan\Tagged;

use DecodeLabs\PHPStan\PropertyReflection;

use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\PropertyReflection as PropertyReflectionInterface;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\IntegerType;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;

class XmlElementReflectionExtension implements PropertiesClassReflectionExtension, BrokerAwareExtension
{
    private $broker;

    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }


    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        return $classReflection->getName() === 'DecodeLabs\\Tagged\\Xml\\Element';
    }

    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflectionInterface
    {
        return new PropertyReflection($classReflection, new ArrayType(
            new IntegerType(), new ObjectType($classReflection->getName())
        ));
    }
}
