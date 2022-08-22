<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Tagged;

use DecodeLabs\PHPStan\MethodReflection;
use DecodeLabs\Tagged\Factory as HtmlFactory;

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

    public function setBroker(Broker $broker): void
    {
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
        return $classReflection->getName() === HtmlFactory::class;
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflectionInterface {
        $method = $this->broker->getClass(HtmlFactory::class)->getNativeMethod('el');

        /**
         * @var FunctionVariant $variant
         */
        $variant = $method->getVariants()[0];
        $params = array_slice($variant->getParameters(), 1);
        $newVariant = MethodReflection::alterVariant($variant, $params);

        $output = new MethodReflection($classReflection, $methodName, [$newVariant]);
        $output->setStatic(true);

        return $output;
    }
}
