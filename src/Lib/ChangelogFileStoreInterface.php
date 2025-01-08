<?php
declare(strict_types=1);

namespace App\Lib;

interface ChangelogFileStoreInterface
{
    /**
     * @param list<ChangelogHistory> $changelogs
     */
    public function storeSquareAPIsAndSDKs(array $changelogs): void;

    /**
     * @return list<ChangelogHistory>
     */
    public function loadSquareAPIsAndSDKs(): array;
}
