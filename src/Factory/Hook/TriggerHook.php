<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Factory\Hook;

use Ulrack\Services\Common\ServiceFactoryInterface;
use Ulrack\Services\Common\Hook\AbstractServiceFactoryHook;

class TriggerHook extends AbstractServiceFactoryHook
{
    /**
     * Hooks in after the creation of a service.
     *
     * @param string $serviceKey
     * @param mixed $return
     * @param array $parameters
     *
     * @return array
     */
    public function postCreate(
        string $serviceKey,
        $return,
        array $parameters = []
    ): array {
        $services = $this->getServices();
        if (isset($services['triggers']['services'][$serviceKey])) {
            $serviceFactory = $this->getInternalService('service-factory');
            foreach ($services['triggers']['services'][$serviceKey] as $trigger) {
                $serviceFactory->create('triggers.' . $trigger);
            }
        }

        return [
            'serviceKey' => $serviceKey,
            'return' => $return,
            'parameters' => $parameters
        ];
    }
}
