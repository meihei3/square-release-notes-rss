<?php
declare(strict_types=1);

namespace App\Lib;

final readonly class ChangelogVersionSummary
{
    public function __construct(
        public string $target,
        public string $version,
        public string $url,
    ) {}
}
