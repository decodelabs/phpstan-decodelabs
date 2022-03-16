<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Metamorph;

use DecodeLabs\PHPStan\MethodReflection;

use DecodeLabs\Metamorph;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\Native\NativeParameterReflection as ParameterReflection;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;

class ReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    protected $broker;

    /**
     * @param \PHPStan\Broker\Broker $broker
     */
    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    /**
     * Returns the current broker.
     *
     * @return \PHPStan\Broker\Broker
     */
    public function getBroker(): Broker
    {
        return $this->broker;
    }

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return $classReflection->getName() === Metamorph::class;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
        $output = new MethodReflection($classReflection, $methodName, $this->getElementVariants());
        $output->setStatic(true);
        return $output;
    }

    /**
     * @return array<FunctionVariant>
     */
    protected function getElementVariants(): array
    {
        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                [
                    new ParameterReflection('content', true, TypeCombinator::addNull(new MixedType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('options', true, TypeCombinator::addNull(new ArrayType(
                        new StringType(),
                        new MixedType()
                    )), PassedByReference::createNo(), false, null)
                ],
                false,
                TypeCombinator::addNull(new StringType())
            )
        ];
    }
}
