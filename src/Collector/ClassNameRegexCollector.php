<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Collector;

use LogicException;
use Qossmic\Deptrac\AstRunner\AstMap;
use Qossmic\Deptrac\AstRunner\AstMap\AstClassReference;

class ClassNameRegexCollector implements CollectorInterface
{
    public function getType(): string
    {
        return 'classNameRegex';
    }

    public function satisfy(
        array $configuration,
        AstClassReference $astClassReference,
        AstMap $astMap,
        Registry $collectorRegistry
    ): bool {
        return $astClassReference->getClassLikeName()->match($this->getPattern($configuration));
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function getPattern(array $configuration): string
    {
        if (!isset($configuration['regex']) || !is_string($configuration['regex'])) {
            throw new LogicException('ClassNameRegexCollector needs the regex configuration.');
        }

        return $configuration['regex'];
    }
}
