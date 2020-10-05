<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan\Tagged;

use DecodeLabs\Tagged\Html\Factory as HtmlFactory;
use DecodeLabs\Tagged\Html\Element;

use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\PHPStan\StaticMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\Native\NativeParameterReflection as ParameterReflection;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Generic\TemplateTypeMap;

class HtmlReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
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
        return $classReflection->getName() === HtmlFactory::class;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
        return (new MethodReflection($classReflection, $methodName, $this->getElementVariants()));
    }

    protected function getElementVariants()
    {
        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                [
                    new ParameterReflection('content', true, TypeCombinator::addNull(new MixedType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('attributes', true, TypeCombinator::addNull(new ArrayType(
                        new StringType(), new MixedType()
                    )), PassedByReference::createNo(), false, null)
                ],
                false,
                new ObjectType(Element::class)
            )
        ];
    }
}
