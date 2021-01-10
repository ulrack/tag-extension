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

    /**
     * @covers ::compile
     * @covers ::trimKey
     *
     * @return void
     */
    public function testCompileSorted(): void
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
                'service' => 'services.my.service',
                'sortOrder' => 100
            ],
            'my.second.tag' => [
                'trigger' => 'triggers.my.trigger',
                'service' => 'services.my.second.service',
                'sortOrder' => 0
            ],
            'my.third.tag' => [
                'trigger' => 'triggers.my.trigger',
                'service' => 'services.my.third.service',
                'sortOrder' => 100
            ],
            'my.fourth.tag' => [
                'trigger' => 'triggers.my.trigger',
                'service' => 'services.my.fourth.service'
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
                        'services.my.second.service',
                        'services.my.service',
                        'services.my.third.service',
                        'services.my.fourth.service'
                    ]
                ]
            ]
        ], $subject->compile($services));
    }
}
