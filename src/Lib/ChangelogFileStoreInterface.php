<?php
declare(strict_types=1);

namespace App\Lib;

interface ChangelogFileStoreInterface
{
    /**
     * @param list<Changelog> $changelogs
     */
    public function storeSquareAPIsAndSDKs(array $changelogs): void;

    /**
     * @return list<Changelog>
     */
    public function loadSquareAPIsAndSDKs(): array;
}
