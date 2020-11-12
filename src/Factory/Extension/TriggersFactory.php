<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Factory\Extension;

use Ulrack\Services\Exception\DefinitionNotFoundException;
use Ulrack\Services\Common\AbstractServiceFactoryExtension;

class TriggersFactory extends AbstractServiceFactoryExtension
{
    /**
     * Contains the results which have been invoked.
     *
     * @var array
     */
    private $services = [];

    /**
     * Register a value to a service key.
     *
     * @param string $serviceKey
     * @param mixed $value
     *
     * @return void
     */
    public function registerService(string $serviceKey, $value): void
    {
        $this->services[$serviceKey] = $value;
    }

    /**
     * Invoke the services and return the result.
     *
     * @param string $serviceKey
     *
     * @return mixed
     *
     * @throws DefinitionNotFoundException When the definition can not be found.
     */
    public function create(string $serviceKey)
    {
        $serviceKey = $this->preCreate(
            $serviceKey,
            $this->getParameters()
        )['serviceKey'];

        $internalKey = preg_replace(
            sprintf('/^%s\\./', preg_quote($this->getKey())),
            '',
            $serviceKey,
            1
        );

        if (!isset($this->services[$internalKey])) {
            $services = $this->getServices()[$this->getKey()]['triggers'] ?? [];
            if (!isset($services[$internalKey])) {
                throw new DefinitionNotFoundException($serviceKey);
            }

            $result = [];
            $this->registerService($internalKey, $result);
            foreach ($services[$internalKey] ?? [] as $service) {
                $result[] = $this->superCreate($service);
            }

            $this->registerService($internalKey, $result);
        }

        return $this->postCreate(
            $serviceKey,
            $this->services[$internalKey],
            $this->getParameters()
        )['return'];
    }
}
