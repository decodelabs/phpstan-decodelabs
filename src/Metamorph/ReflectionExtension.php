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
        $method = $this->broker->getClass(Metamorph::class)->getNativeMethod('convert');
        $variant = $method->getVariants()[0];
        $params = $variant->getParameters();
        array_shift($params);

        $output = new MethodReflection($classReflection, $methodName, [$variant]);
        $output->setStatic(true);
        return $output;
    }
}
