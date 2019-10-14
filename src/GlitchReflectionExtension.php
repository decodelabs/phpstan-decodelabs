<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;

class GlitchReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Glitch\\Exception\\Factory':
                return true;

            case 'DecodeLabs\\Glitch\\Context':
                return preg_match('|[.\\/]|', $methodName) || preg_match('/^[A-Z]/', $methodName);

            default:
                return false;
        }
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Glitch\\Exception\\Factory':
                return (new MethodReflection($classReflection, $methodName))
                    ->setStatic(true);

            case 'DecodeLabs\\Glitch\\Context':
                return (new MethodReflection($classReflection, $methodName));

            default:
                return (new MethodReflection($classReflection, $methodName));
        }

        return (new MethodReflection($classReflection, $methodName))
            ->setStatic(true);
    }
}
