<?php
declare(strict_types=1);

namespace App\Lib;

interface SquareReleaseNotesFetchClientInterface
{
    /**
     * @return list<Changelog>
     */
    public function fetchSquareAPIsAndSDKsChangeLogList(): array;
}
