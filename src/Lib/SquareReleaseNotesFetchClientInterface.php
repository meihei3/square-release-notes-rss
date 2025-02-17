<?php

declare(strict_types=1);

namespace App\Lib;

interface SquareReleaseNotesFetchClientInterface
{
    /**
     * @return list<ChangelogHistory>
     */
    public function fetchSquareAPIsAndSDKsChangelogHistoryList(): array;

    /**
     * @return list<ChangelogHistory>
     */
    public function fetchMobileSDKsChangelogHistoryList(): array;
}
