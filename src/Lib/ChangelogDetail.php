<?php
declare(strict_types=1);

namespace App\Lib;

final readonly class ChangelogDetail
{
    /**
     * @param list<ChangelogDetailLine> $items
     */
    public function __construct(
        public string $category,
        public array $items,
    ) {}
}
