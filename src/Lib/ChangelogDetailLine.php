<?php
declare(strict_types=1);

namespace App\Lib;

final readonly class ChangelogDetailLine
{
    public function __construct(
        public string $title,
        public array $content,
    ) {}
}
