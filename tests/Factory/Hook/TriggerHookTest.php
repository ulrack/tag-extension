<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Tests\Factory\Hook;

use stdClass;
use PHPUnit\Framework\TestCase;
use Ulrack\TagExtension\Factory\Hook\TriggerHook;
use Ulrack\Services\Common\ServiceFactoryInterface;

/**
 * @coversDefaultClass \Ulrack\TagExtension\Factory\Hook\TriggerHook
 */
class TriggerHookTest extends TestCase
{
    /**
     * @covers ::postCreate
     * @covers ::__construct
     *
     * @return void
     */
    public function testPostCreate(): void
    {
        $serviceKey = 'services.my.trigger.service';
        $return = new stdClass();

        $serviceFactory = $this->createMock(ServiceFactoryInterface::class);
        $key = 'global';
        $services = [
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
        ];

        $serviceFactory->expects(static::once())
            ->method('create')
            ->with('triggers.my.trigger')
            ->willReturn([]);

        $internalServices = ['service-factory' => $serviceFactory];
        $subject = new TriggerHook($key, [], $services, $internalServices);


        $this->assertEquals(
            [
                'serviceKey' => $serviceKey,
                'return' => $return,
                'parameters' => []
            ],
            $subject->postCreate($serviceKey, $return, [])
        );
    }
}
