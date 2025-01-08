<?php
declare(strict_types=1);

namespace App\Lib;

interface ChangelogHistoryFileStoreInterface
{
    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function storeSquareAPIsAndSDKs(array $changelogHistories): void;

    /**
     * @return list<ChangelogHistory>
     */
    public function loadSquareAPIsAndSDKs(): array;
}
