<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Tests\Component\Compiler\Extension;

use PHPUnit\Framework\TestCase;
use Ulrack\Services\Common\ServiceRegistryInterface;
use GrizzIt\Validator\Component\Logical\AlwaysValidator;
use Ulrack\TagExtension\Component\Compiler\Extension\TriggersCompiler;

/**
 * @coversDefaultClass \Ulrack\TagExtension\Component\Compiler\Extension\TriggersCompiler
 */
class TriggersCompilerTest extends TestCase
{
    /**
     * @covers ::compile
     * @covers ::trimKey
     *
     * @return void
     */
    public function testCompileEmpty(): void
    {
        $registry = $this->createMock(ServiceRegistryInterface::class);
        $validator = new AlwaysValidator(true);
        $getHooks = (function () {
            return [];
        });

        $subject = new TriggersCompiler(
            $registry,
            'triggers',
            $validator,
            [],
            $getHooks
        );

        $services = ['triggers' => [], 'tags' => []];
        $this->assertEquals(
            ['triggers' => ['services' => [], 'triggers' => []]],
            $subject->compile($services)
        );
    }

    /**
     * @covers ::compile
     * @covers ::trimKey
     *
     * @return void
     */
    public function testCompile(): void
    {
        $registry = $this->createMock(ServiceRegistryInterface::class);
        $validator = new AlwaysValidator(true);
        $getHooks = (function () {
            return [];
        });

        $subject = new TriggersCompiler(
            $registry,
            'triggers',
            $validator,
            [],
            $getHooks
        );

        $services = ['triggers' => [
            'my.trigger' => [
                'service' => 'services.my.trigger.service'
            ]
        ], 'tags' => [
            'my.tag' => [
                'trigger' => 'triggers.my.trigger',
                'service' => 'services.my.service'
            ]
        ]];
        $this->assertEquals([
            'triggers' => [
                'services' => [
                    'services.my.trigger.service' => [
                        'my.trigger'
                    ]
                ],
                'triggers' => [
                    'my.trigger' => [
                        'services.my.service'
                    ]
                ]
            ]
        ], $subject->compile($services));
    }
}
