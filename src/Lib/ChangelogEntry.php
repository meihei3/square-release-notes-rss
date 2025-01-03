<?php
declare(strict_types=1);

namespace App\Lib;

final readonly class ChangelogEntry
{
    /**
     * @param list<ChangelogVersionSummary> $versionSummaries
     * @param list<ChangelogDetail> $details
     */
    public function __construct(
        public array $versionSummaries,
        public array $details,
    ) {}
}
