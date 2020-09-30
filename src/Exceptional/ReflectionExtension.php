<?php
/**
 * This file is part of the Glitch package
 * @license http://opensource.org/licenses/MIT
 */
declare(strict_types=1);
namespace DecodeLabs\PHPStan\Exceptional;

use DecodeLabs\PHPStan\MethodReflection;
use PHPStan\Reflection\Native\NativeParameterReflection as ParameterReflection;

use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\MethodReflection as MethodReflectionInterface;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\PassedByReference;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Generic\TemplateTypeMap;

class ReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Exceptional':
                return preg_match('|[.\\\\/]|', $methodName) || preg_match('/^[A-Z]/', $methodName);
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflectionInterface
    {
        $static = false;

        switch ($classReflection->getName()) {
            case 'DecodeLabs\\Exceptional':
                $static = true;
                $variants = $this->getExceptionVariants();
                break;

            default:
                $variants = [
                    new FunctionVariant(
                        TemplateTypeMap::createEmpty(),
                        null,
                        [],
                        true,
                        new MixedType()
                    ),
                ];

                if ($classReflection->isAnonymous() && strstr((string)$classReflection->getFileName(), 'src/Veneer/Binding.php')) {
                    $static = true;
                }
                break;
        }

        return (new MethodReflection($classReflection, $methodName, $variants))
            ->setStatic($static);
    }

    protected function getExceptionVariants()
    {
        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                [
                    new ParameterReflection('message', true, TypeCombinator::addNull(new StringType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('params', true, TypeCombinator::addNull(new ArrayType(
                        new StringType(), new MixedType()
                    )), PassedByReference::createNo(), false, null),
                    new ParameterReflection('data', true, TypeCombinator::addNull(new MixedType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('previous', true, TypeCombinator::addNull(new ObjectType('Throwable')), PassedByReference::createNo(), false, null),
                    new ParameterReflection('code', true, TypeCombinator::addNull(new IntegerType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('file', true, TypeCombinator::addNull(new StringType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('line', true, TypeCombinator::addNull(new IntegerType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('http', true, TypeCombinator::addNull(new IntegerType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('namespace', true, TypeCombinator::addNull(new StringType()), PassedByReference::createNo(), false, null),
                    new ParameterReflection('interfaces', true, TypeCombinator::addNull(new ArrayType(
                        new IntegerType(), new StringType()
                    )), PassedByReference::createNo(), false, null),
                    new ParameterReflection('traits', true, TypeCombinator::addNull(new ArrayType(
                        new IntegerType(), new StringType()
                    )), PassedByReference::createNo(), false, null)
                ],
                false,
                new ObjectType('Exception')
            )
        ];
    }
}
