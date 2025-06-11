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

    /**
     * @return list<ChangelogHistory>
     */
    public function fetchWebPaymentsSDKChangelogHistoryList(): array;

    /**
     * @return list<ChangelogHistory>
     */
    public function fetchPaymentFormChangelogHistoryList(): array;

    /**
     * @return list<ChangelogHistory>
     */
    public function fetchRequirementsChangelogHistoryList(): array;
}
