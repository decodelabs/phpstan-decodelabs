<?php

/**
 * @package PHPStanDecodeLabs
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\PHPStan\Terminus;

use DecodeLabs\PHPStan\StaticMethodReflection;
use DecodeLabs\Terminus\Context;
use DecodeLabs\Terminus\Session;
use DecodeLabs\Veneer\Proxy;

use Exception;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;

use PHPStan\Reflection\MethodsClassReflectionExtension;

class ReflectionExtension implements MethodsClassReflectionExtension, BrokerAwareExtension
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
        $class = $classReflection->getName();

        if (is_a($class, Proxy::class, true)) {
            return $this->getBroker()->getClass($class::VENEER_TARGET)->hasMethod($methodName);
        } elseif ($class === Context::class) {
            return $this->getBroker()->getClass(Session::class)->hasMethod($methodName);
        } else {
            return false;
        }
    }

    public function getMethod(
        ClassReflection $classReflection,
        string $methodName
    ): MethodReflectionInterface {
        $class = $classReflection->getName();

        if (is_a($class, Proxy::class, true)) {
            return new StaticMethodReflection(
                $this->getBroker()->getClass($class::VENEER_TARGET)->getMethod($methodName, new OutOfClassScope())
            );
        }

        if ($class === Context::class) {
            return new StaticMethodReflection(
                $this->getBroker()->getClass(Session::class)->getMethod($methodName, new OutOfClassScope())
            );
        }

        throw new Exception('Unable to get method');
    }
}
