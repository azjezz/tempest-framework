<?php

declare(strict_types=1);

namespace Tempest\Container\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tempest\Container\Exceptions\CannotAutowireException;
use Tempest\Container\Exceptions\CannotInstantiateDependencyException;
use Tempest\Container\Exceptions\CannotResolveTaggedDependency;
use Tempest\Container\Exceptions\CircularDependencyException;
use Tempest\Container\Exceptions\InvalidCallableException;
use Tempest\Container\GenericContainer;
use Tempest\Container\Tests\Fixtures\BuiltinArrayClass;
use Tempest\Container\Tests\Fixtures\BuiltinDependencyArrayInitializer;
use Tempest\Container\Tests\Fixtures\BuiltinDependencyBoolInitializer;
use Tempest\Container\Tests\Fixtures\BuiltinDependencyStringInitializer;
use Tempest\Container\Tests\Fixtures\BuiltinTypesWithDefaultsClass;
use Tempest\Container\Tests\Fixtures\CallContainerObjectE;
use Tempest\Container\Tests\Fixtures\CircularWithInitializerA;
use Tempest\Container\Tests\Fixtures\CircularWithInitializerBInitializer;
use Tempest\Container\Tests\Fixtures\ClassWithSingletonAttribute;
use Tempest\Container\Tests\Fixtures\ContainerObjectA;
use Tempest\Container\Tests\Fixtures\ContainerObjectB;
use Tempest\Container\Tests\Fixtures\ContainerObjectC;
use Tempest\Container\Tests\Fixtures\ContainerObjectD;
use Tempest\Container\Tests\Fixtures\ContainerObjectDInitializer;
use Tempest\Container\Tests\Fixtures\ContainerObjectE;
use Tempest\Container\Tests\Fixtures\ContainerObjectEInitializer;
use Tempest\Container\Tests\Fixtures\DependencyWithBuiltinDependencies;
use Tempest\Container\Tests\Fixtures\DependencyWithTaggedDependency;
use Tempest\Container\Tests\Fixtures\ImplementsInterfaceA;
use Tempest\Container\Tests\Fixtures\InjectA;
use Tempest\Container\Tests\Fixtures\InjectB;
use Tempest\Container\Tests\Fixtures\InterfaceA;
use Tempest\Container\Tests\Fixtures\IntersectionInitializer;
use Tempest\Container\Tests\Fixtures\InvokableClass;
use Tempest\Container\Tests\Fixtures\InvokableClassWithParameters;
use Tempest\Container\Tests\Fixtures\OptionalTypesClass;
use Tempest\Container\Tests\Fixtures\SingletonClass;
use Tempest\Container\Tests\Fixtures\SingletonInitializer;
use Tempest\Container\Tests\Fixtures\TaggedDependency;
use Tempest\Container\Tests\Fixtures\TaggedDependencyCliInitializer;
use Tempest\Container\Tests\Fixtures\TaggedDependencyWebInitializer;
use Tempest\Container\Tests\Fixtures\UnionImplementation;
use Tempest\Container\Tests\Fixtures\UnionInitializer;
use Tempest\Container\Tests\Fixtures\UnionInterfaceA;
use Tempest\Container\Tests\Fixtures\UnionInterfaceB;
use Tempest\Container\Tests\Fixtures\UnionTypesClass;

use function Tempest\reflect;

/**
 * @internal
 */
final class ContainerTest extends TestCase
{
    public function test_get_with_autowire(): void
    {
        $container = new GenericContainer();

        $b = $container->get(ContainerObjectB::class);

        static::assertInstanceOf(ContainerObjectB::class, $b);
        static::assertInstanceOf(ContainerObjectA::class, $b->a);
    }

    public function test_get_with_definition(): void
    {
        $container = new GenericContainer();

        $container->register(
            ContainerObjectC::class,
            fn () => new ContainerObjectC(prop: 'test'),
        );

        $c = $container->get(ContainerObjectC::class);

        static::assertEquals('test', $c->prop);
    }

    public function test_get_with_initializer(): void
    {
        $container = new GenericContainer()->setInitializers([
            ContainerObjectD::class => ContainerObjectDInitializer::class,
        ]);

        $d = $container->get(ContainerObjectD::class);

        static::assertEquals('test', $d->prop);
    }

    public function test_singleton(): void
    {
        $container = new GenericContainer();

        $container->singleton(SingletonClass::class, fn () => new SingletonClass());

        $instance = $container->get(SingletonClass::class);

        static::assertEquals(1, $instance::$count);

        $instance = $container->get(SingletonClass::class);

        static::assertEquals(1, $instance::$count);
    }

    public function test_initialize_with_can_initializer(): void
    {
        $container = new GenericContainer();

        $container->addInitializer(ContainerObjectEInitializer::class);

        $object = $container->get(ContainerObjectE::class);

        static::assertInstanceOf(ContainerObjectE::class, $object);
    }

    public function test_call_tries_to_transform_unmatched_values(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(ContainerObjectEInitializer::class);

        $classToCall = new CallContainerObjectE();

        $return = $container->invoke(reflect($classToCall)->getMethod('method'), input: '1');
        static::assertInstanceOf(ContainerObjectE::class, $return);
        static::assertSame('default', $return->id);

        $return = $container->invoke(reflect($classToCall)->getMethod('method'), input: new ContainerObjectE('other'));
        static::assertInstanceOf(ContainerObjectE::class, $return);
        static::assertSame('other', $return->id);
    }

    public function test_arrays_are_automatically_created(): void
    {
        $container = new GenericContainer();

        /**
         * @var BuiltinArrayClass $class
         */
        $class = $container->get(BuiltinArrayClass::class);

        static::assertEmpty($class->anArray);
    }

    public function test_builtin_defaults_are_used(): void
    {
        $container = new GenericContainer();

        /**
         * @var BuiltinTypesWithDefaultsClass $class
         */
        $class = $container->get(BuiltinTypesWithDefaultsClass::class);

        static::assertSame('This is a default value', $class->aString);
    }

    public function test_optional_types_resolve_to_null(): void
    {
        $container = new GenericContainer();

        /**
         * @var OptionalTypesClass $class
         */
        $class = $container->get(OptionalTypesClass::class);

        static::assertNull($class->aString);
    }

    public function test_union_types_iterate_to_resolution(): void
    {
        $container = new GenericContainer();

        /** @var UnionTypesClass $class */
        $class = $container->get(UnionTypesClass::class);

        static::assertInstanceOf(UnionTypesClass::class, $class);
        static::assertInstanceOf(ContainerObjectA::class, $class->input);
    }

    public function test_singleton_initializers(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(SingletonInitializer::class);

        $a = $container->get(ContainerObjectE::class);
        $b = $container->get(ContainerObjectE::class);
        static::assertSame(spl_object_id($a), spl_object_id($b));
    }

    public function test_union_initializers(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(UnionInitializer::class);

        $a = $container->get(UnionInterfaceA::class);
        $b = $container->get(UnionInterfaceB::class);

        static::assertInstanceOf(UnionImplementation::class, $a);
        static::assertInstanceOf(UnionImplementation::class, $b);
    }

    public function test_intersection_initializers(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(IntersectionInitializer::class);

        $a = $container->get(UnionInterfaceA::class);
        $b = $container->get(UnionInterfaceB::class);

        static::assertInstanceOf(UnionImplementation::class, $a);
        static::assertInstanceOf(UnionImplementation::class, $b);
    }

    public function test_circular_with_initializer_log(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(CircularWithInitializerBInitializer::class);
        static::assertContains(CircularWithInitializerBInitializer::class, $container->getInitializers());

        try {
            $container->get(CircularWithInitializerA::class);
        } catch (CircularDependencyException $circularDependencyException) {
            static::assertStringContainsString('CircularWithInitializerA', $circularDependencyException->getMessage());
            static::assertStringContainsString('CircularWithInitializerB', $circularDependencyException->getMessage());
            static::assertStringContainsString('CircularWithInitializerBInitializer', $circularDependencyException->getMessage());
            static::assertStringContainsString('CircularWithInitializerC', $circularDependencyException->getMessage());
            static::assertStringContainsString(__FILE__, $circularDependencyException->getMessage());
        }
    }

    public function test_tagged_singleton(): void
    {
        $container = new GenericContainer();

        $container->singleton(
            TaggedDependency::class,
            new TaggedDependency('web'),
            tag: 'web',
        );

        $container->singleton(
            TaggedDependency::class,
            new TaggedDependency('cli'),
            tag: 'cli',
        );

        static::assertSame('web', $container->get(TaggedDependency::class, 'web')->name);
        static::assertSame('cli', $container->get(TaggedDependency::class, 'cli')->name);
    }

    public function test_tagged_singleton_with_initializer(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(TaggedDependencyWebInitializer::class);
        $container->addInitializer(TaggedDependencyCliInitializer::class);

        static::assertSame('web', $container->get(TaggedDependency::class, 'web')->name);
        static::assertSame('cli', $container->get(TaggedDependency::class, 'cli')->name);
    }

    public function test_tagged_singleton_exception(): void
    {
        $container = new GenericContainer();

        $this->expectException(CannotResolveTaggedDependency::class);

        $container->get(TaggedDependency::class, 'web');
    }

    public function test_autowired_tagged_dependency(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(TaggedDependencyWebInitializer::class);

        $dependency = $container->get(DependencyWithTaggedDependency::class);
        static::assertSame('web', $dependency->dependency->name);
    }

    public function test_autowired_tagged_dependency_exception(): void
    {
        $container = new GenericContainer();

        try {
            $container->get(DependencyWithTaggedDependency::class);
        } catch (CannotResolveTaggedDependency $cannotResolveTaggedDependency) {
            static::assertStringContainsStringIgnoringLineEndings(
                <<<'TXT'
                	┌── DependencyWithTaggedDependency::__construct(TaggedDependency $dependency)
                	└── Tempest\Container\Tests\Fixtures\TaggedDependency
                TXT,
                $cannotResolveTaggedDependency->getMessage(),
            );
        }
    }

    public function test_singleton_on_class(): void
    {
        $container = new GenericContainer();

        $a = $container->get(ClassWithSingletonAttribute::class);

        $a->flag = true;

        $b = $container->get(ClassWithSingletonAttribute::class);

        static::assertTrue($b->flag);
    }

    public function test_invoke_callable(): void
    {
        $container = new GenericContainer();
        $container->singleton(SingletonClass::class, fn () => new SingletonClass());

        static::assertEquals('foo', $container->invoke(InvokableClass::class));
        static::assertEquals('foobar', $container->invoke([new InvokableClass(), 'execute']));
        static::assertEquals('bar', $container->invoke(InvokableClassWithParameters::class, param: 'bar'));
        static::assertInstanceOf(ReflectionClass::class, $container->invoke(fn (SingletonClass $class) => new ReflectionClass($class)));
    }

    public function test_call_function_with_parameters(): void
    {
        $container = new GenericContainer();
        $container->singleton(SingletonClass::class, fn () => new SingletonClass());

        $result = $container->invoke(
            callable: fn (SingletonClass $class, string $prefix) => $prefix . $class::class,
            prefix: 'My resolved class is ',
        );

        static::assertEquals('My resolved class is Tempest\Container\Tests\Fixtures\SingletonClass', $result);
    }

    public function test_call_function_with_dependencies_and_parameters(): void
    {
        $container = new GenericContainer();

        $result = $container->invoke(fn (string $param) => $param, param: 'foo');

        static::assertEquals('foo', $result);
    }

    public function test_call_function_with_unresolvable_parameters(): void
    {
        $this->expectException(CannotAutowireException::class);
        $this->expectExceptionMessageMatches('/because string cannot be resolved/');

        $container = new GenericContainer();
        $container->invoke(fn (string $param) => $param);
    }

    public function test_call_invalid_closure(): void
    {
        $this->expectException(InvalidCallableException::class);
        $this->expectExceptionMessage('[array_map] cannot be invoked through the container.');

        $container = new GenericContainer();
        $container->invoke('array_map');
    }

    public function test_invoke_closure_with_function(): void
    {
        GenericContainer::setInstance($container = new GenericContainer());
        $container->singleton(SingletonClass::class, fn () => new SingletonClass());

        $result = \Tempest\invoke(fn (SingletonClass $class) => $class::class);

        static::assertEquals(SingletonClass::class, $result);
    }

    public function test_builtin_dependency_initializer(): void
    {
        $container = new GenericContainer();
        $container->addInitializer(BuiltinDependencyArrayInitializer::class);
        $container->addInitializer(BuiltinDependencyBoolInitializer::class);
        $container->addInitializer(BuiltinDependencyStringInitializer::class);

        /** @var DependencyWithBuiltinDependencies $a */
        $a = $container->get(DependencyWithBuiltinDependencies::class);

        static::assertSame('Hallo dependency!', $a->stringValue);
        static::assertSame(['hallo', 'array', 42], $a->arrayValue);
        static::assertTrue($a->boolValue);
    }

    public function test_inject(): void
    {
        $container = new GenericContainer();

        /** @var InjectA $a */
        $a = $container->get(InjectA::class);

        static::assertInstanceOf(InjectB::class, $a->getB());
    }

    public function test_unregister(): void
    {
        $container = new GenericContainer();

        $container->register(InterfaceA::class, fn () => new ImplementsInterfaceA());

        static::assertInstanceOf(ImplementsInterfaceA::class, $container->get(InterfaceA::class));

        $container->unregister(InterfaceA::class);

        $this->expectException(CannotInstantiateDependencyException::class);

        $container->get(InterfaceA::class);
    }

    public function test_unregister_singleton(): void
    {
        $container = new GenericContainer();

        $container->singleton(InterfaceA::class, $instance = new ImplementsInterfaceA());

        static::assertInstanceOf(ImplementsInterfaceA::class, $container->get(InterfaceA::class));
        static::assertSame($instance, $container->get(InterfaceA::class));

        $container->unregister(InterfaceA::class);

        $this->expectException(CannotInstantiateDependencyException::class);

        $container->get(InterfaceA::class);
    }

    public function test_has(): void
    {
        $container = new GenericContainer();

        static::assertFalse($container->has(InterfaceA::class));

        $container->register(InterfaceA::class, fn () => new ImplementsInterfaceA());

        static::assertTrue($container->has(InterfaceA::class));
    }

    public function test_has_singleton(): void
    {
        $container = new GenericContainer();

        static::assertFalse($container->has(InterfaceA::class));

        $container->singleton(InterfaceA::class, new ImplementsInterfaceA());

        static::assertTrue($container->has(InterfaceA::class));
    }

    public function test_has_tagged_singleton(): void
    {
        $container = new GenericContainer();

        static::assertFalse($container->has(TaggedDependency::class, 'web'));

        $container->singleton(
            TaggedDependency::class,
            new TaggedDependency('web'),
            tag: 'web',
        );

        static::assertTrue($container->has(TaggedDependency::class, 'web'));
    }
}
