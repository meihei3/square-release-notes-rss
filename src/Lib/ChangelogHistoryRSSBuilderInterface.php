<?php

declare(strict_types=1);

namespace App\Lib;

interface ChangelogHistoryRSSBuilderInterface
{
    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function buildSquareAPIsAndSDKs(array $changelogHistories): void;

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function buildMobileSDKs(array $changelogHistories): void;

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function buildWebPaymentsSDK(array $changelogHistories): void;

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function buildPaymentForm(array $changelogHistories): void;
}
