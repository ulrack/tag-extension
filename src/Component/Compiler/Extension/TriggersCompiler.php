<?php

/**
 * Copyright (C) GrizzIT, Inc. All rights reserved.
 * See LICENSE for license details.
 */

namespace Ulrack\TagExtension\Component\Compiler\Extension;

use Ulrack\Services\Common\AbstractServiceCompilerExtension;

class TriggersCompiler extends AbstractServiceCompilerExtension
{
    /**
     * Compile the services.
     *
     * @param array $services
     *
     * @return array
     */
    public function compile(array $services): array
    {
        $services = $this->preCompile(
            $services,
            $this->getParameters()
        )['services'];

        $inputServices = $services;
        foreach ($services['tags'] as $key => $tag) {
            $triggerKey = $this->trimKey($tag['trigger']);
            if (isset($services['triggers'][$triggerKey])) {
                $services['triggers'][$triggerKey]['tags']
                    [$tag['sortOrder'] ?? 1000][] = $tag['service'];
            }
        }



        $triggers = ['services' => [], 'triggers' => []];
        foreach ($services['triggers'] as $triggerKey => $trigger) {
            ksort($trigger['tags']);
            $trigger['tags'] = array_merge(...$trigger['tags']);
            if (isset($trigger['service'])) {
                $triggers['services'][$trigger['service']][] = $triggerKey;
            }

            $triggers['triggers'][$triggerKey] = $trigger['tags'] ?? [];
        }

        unset($services['tags']);
        $services['triggers'] = $triggers;

        return $this->postCompile(
            $inputServices,
            $services,
            $this->getParameters()
        )['return'];
    }

    /**
     * Removes the internal key from the tag.
     *
     * @param string $key
     *
     * @return string
     */
    private function trimKey(string $key): string
    {
        return preg_replace(
            sprintf('/^%s\\./', preg_quote($this->getKey())),
            '',
            $key,
            1
        );
    }
}
