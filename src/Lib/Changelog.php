<?php
declare(strict_types=1);

namespace App\Lib;

final readonly class Changelog
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        public string $version,
        public string $url,
        public array  $tags,
    ) {}
}
