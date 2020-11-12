<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Tests\Factory\Extension;

use stdClass;
use PHPUnit\Framework\TestCase;
use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\Services\Exception\DefinitionNotFoundException;
use Ulrack\TagExtension\Factory\Extension\TriggersFactory;

/**
 * @coversDefaultClass \Ulrack\TagExtension\Factory\Extension\TriggersFactory
 */
class TriggersFactoryTest extends TestCase
{
    /**
     * @covers ::registerService
     * @covers ::create
     *
     * @return void
     */
    public function testCreate(): void
    {
        $serviceFactory = $this->createMock(ServiceFactoryInterface::class);
        $result = new stdClass();
        $subject = new TriggersFactory(
            $serviceFactory,
            'triggers',
            [],
            [
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
            ],
            (function () {
                return [];
            }),
            []
        );
        $serviceFactory->expects(static::once())
            ->method('create')
            ->with('services.my.service')
            ->willReturn($result);

        $this->assertEquals([$result], $subject->create('triggers.my.trigger'));
    }

    /**
     * @covers ::registerService
     * @covers ::create
     *
     * @return void
     */
    public function testCreateNoDefinition(): void
    {
        $serviceFactory = $this->createMock(ServiceFactoryInterface::class);
        $subject = new TriggersFactory(
            $serviceFactory,
            'triggers',
            [],
            [
                'triggers' => [
                    'services' => [],
                    'triggers' => []
                ]
            ],
            (function () {
                return [];
            }),
            []
        );

        $this->expectException(DefinitionNotFoundException::class);
        $subject->create('triggers.my.trigger');
    }
}
