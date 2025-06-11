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

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function storeMobileSDKs(array $changelogHistories): void;

    /**
     * @return list<ChangelogHistory>
     */
    public function loadMobileSDKs(): array;

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function storeWebPaymentsSDK(array $changelogHistories): void;

    /**
     * @return list<ChangelogHistory>
     */
    public function loadWebPaymentsSDK(): array;

    /**
     * @param list<ChangelogHistory> $changelogHistories
     */
    public function storePaymentForm(array $changelogHistories): void;

    /**
     * @return list<ChangelogHistory>
     */
    public function loadPaymentForm(): array;
}
