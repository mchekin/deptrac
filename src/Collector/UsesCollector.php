<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Collector;

use LogicException;
use Qossmic\Deptrac\AstRunner\AstMap;
use Qossmic\Deptrac\AstRunner\AstMap\AstClassReference;
use Qossmic\Deptrac\AstRunner\AstMap\ClassLikeName;

class UsesCollector implements CollectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'uses';
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(
        array $configuration,
        AstClassReference $astClassReference,
        AstMap $astMap,
        Registry $collectorRegistry
    ): bool {
        $interfaceName = $this->getInterfaceName($configuration);

        foreach ($astMap->getClassInherits($astClassReference->getClassLikeName()) as $inherit) {
            if ($inherit->isUses() && $inherit->getClassLikeName()->equals($interfaceName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function getInterfaceName(array $configuration): ClassLikeName
    {
        if (!isset($configuration['uses']) || !is_string($configuration['uses'])) {
            throw new LogicException('UsesCollector needs the trait name as a string.');
        }

        return ClassLikeName::fromFQCN($configuration['uses']);
    }
}
