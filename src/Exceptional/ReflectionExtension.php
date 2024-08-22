<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Exceptional;

use DecodeLabs\Exceptional;
use DecodeLabs\PHPStan\MethodReflection;

use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
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
        $method = $classReflection->getNativeMethod('_phpstan');
        $output = new MethodReflection($classReflection, $methodName, $method->getVariants());
        $output->setStatic(true);
        return $output;
    }
}
