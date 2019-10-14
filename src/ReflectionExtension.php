<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan;

use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;

class ReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Glitch\\Exception\\Factory':
                return true;

            case 'DecodeLabs\\Glitch\\Context':
                return preg_match('|[.\\/]|', $methodName) || preg_match('/^[A-Z]/', $methodName);
        }

        if ($classReflection->isAnonymous() && strstr((string)$classReflection->getFileName(), 'src/Veneer/Binding.php')) {
            return true;
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
        $static = false;

        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Glitch\\Exception\\Factory':
                $static = true;
                $variants = $this->getGlitchVariants();
                break;

            case 'DecodeLabs\\Glitch\\Context':
                $variants = $this->getGlitchVariants();
                break;

            default:
                $variants = [
                    new FunctionVariant([], true, new MixedType()),
                ];

                if ($classReflection->isAnonymous() && strstr((string)$classReflection->getFileName(), 'src/Veneer/Binding.php')) {
                    $static = true;
                }
                break;
        }

        return (new MethodReflection($classReflection, $methodName, $variants))
            ->setStatic($static);
    }

    protected function getGlitchVariants()
    {
        return [
            new FunctionVariant([
                new ParameterReflection('message', TypeCombinator::addNull(new StringType()), true),
                new ParameterReflection('params', TypeCombinator::addNull(new ArrayType(
                    new StringType(), new MixedType()
                )) , true),
                new ParameterReflection('data', TypeCombinator::addNull(new MixedType()), true),
            ], false, new ObjectType('Exception')),
        ];
    }
}
