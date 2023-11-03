<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Exceptional;

use DecodeLabs\Exceptional;
use DecodeLabs\Exceptional\Factory as ExceptionalFactory;
use DecodeLabs\PHPStan\MethodReflection;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\MethodsClassReflectionExtension;

class ReflectionExtension implements
    MethodsClassReflectionExtension,
    BrokerAwareExtension
{
    protected Broker $broker;

    public function setBroker(
        Broker $broker
    ): void {
        $this->broker = $broker;
    }

    public function getBroker(): Broker
    {
        return $this->broker;
    }

    public function hasMethod(
        ClassReflection $classReflection,
        string $methodName
    ): bool {
        if ($classReflection->getName() !== Exceptional::class) {
            return false;
        }

        return
            preg_match('|[.\\\\/]|', $methodName) ||
            preg_match('/^[A-Z]/', $methodName);
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflectionInterface {
        $method = $this->broker->getClass(ExceptionalFactory::class)->getNativeMethod('create');

        /** @var FunctionVariant $variant */
        $variant = $method->getVariants()[0];

        $params = array_slice($variant->getParameters(), 2);
        $newVariant1 = MethodReflection::alterVariant($variant, $params);

        $params = array_slice($variant->getParameters(), 3);
        $newVariant2 = MethodReflection::alterVariant($variant, $params);

        $output = new MethodReflection($classReflection, $methodName, [$newVariant1, $newVariant2]);
        $output->setStatic(true);

        return $output;
    }
}
