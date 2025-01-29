<?php

declare(strict_types=1);

namespace App\Lib;

interface ChangelogHistoryRSSBuilderInterface
{
    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function buildSquareAPIsAndSDKs(array $changelogHistories): void;
}
