<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan\Terminus;

use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Terminus\Context;
use DecodeLabs\Terminus\Session;

use DecodeLabs\PHPStan\StaticMethodReflection;
use PHPStan\Analyser\OutOfClassScope;
use PHPStan\Reflection\Native\NativeParameterReflection as ParameterReflection;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Broker\Broker;

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
        $class = $classReflection->getName();

        if (is_a($class, Proxy::class, true)) {
            return $this->getBroker()->getClass($class::VENEER_TARGET)->hasMethod($methodName);
        } elseif ($class === Context::class) {
            return $this->getBroker()->getClass(Session::class)->hasMethod($methodName);
        } else {
            return false;
        }
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
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

        throw new \Exception('Unable to get method');
    }
}
