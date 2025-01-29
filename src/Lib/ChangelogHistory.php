<?php

declare(strict_types=1);

namespace App\Lib;

final readonly class ChangelogHistory
{
    public function __construct(
        public string $id,
        public string $slug,
        public string $linkBack,
        public string $changelogType,
        public string $changelogDate,
        public string $summary,
        public string $details,
        public array $tags,
    ) {
    }
}
