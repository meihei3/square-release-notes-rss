<?php

declare(strict_types=1);

namespace App\Lib\Implements;

use App\Lib\ChangelogHistoryRSSBuilderInterface;
use DateTime;
use DateTimeInterface;
use Symfony\Component\Clock\ClockInterface;
use Twig\Environment;

use function array_map;
use function file_put_contents;
use function mb_strlen;
use function mb_substr;

final readonly class ChangelogHistoryRSSBuilder implements ChangelogHistoryRSSBuilderInterface
{
    public function __construct(
        private string $publicDirectory,
        private Environment $twig,
        private string $squareDeveloperUrl,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function buildSquareAPIsAndSDKs(array $changelogHistories): void
    {
        $rss = $this->twig->render('connect.xml.twig', [
            'title'       => 'Release Notes: Square APIs and SDKs',
            'link'        => "{$this->squareDeveloperUrl}/docs/changelog/connect",
            'description' => 'Release notes for Square APIs and SDKs.',
            'pubDate'     => $this->clock->now()->format(DateTimeInterface::RSS),
            'items'       => array_map(fn($changelogHistory) => [
                'title'       => $changelogHistory->summary,
                'link'        => "{$this->squareDeveloperUrl}/docs/{$changelogHistory->slug}",
                'description' => mb_strlen($changelogHistory->details) > 240 ? mb_substr(
                    $changelogHistory->details,
                    0,
                    240
                ) . '...' : $changelogHistory->details,
                'pubDate'     => new DateTime($changelogHistory->changelogDate)->format(DateTimeInterface::RSS),
                'guid'        => $changelogHistory->id,
            ], $changelogHistories),
        ]);

        file_put_contents($this->publicDirectory . '/rss/square-apis-and-sdks.xml', $rss);
    }

    /**
     * @inheritDoc
     */
    public function buildMobileSDKs(array $changelogHistories): void
    {
        $rss = $this->twig->render('connect.xml.twig', [
            'title'       => 'Release Notes: Square Mobile SDKs',
            'link'        => "{$this->squareDeveloperUrl}/docs/changelog/mobile",
            'description' => 'Release notes for Square Mobile SDKs.',
            'pubDate'     => $this->clock->now()->format(DateTimeInterface::RSS),
            'items'       => array_map(fn($changelogHistory) => [
                'title'       => $changelogHistory->summary,
                'link'        => "{$this->squareDeveloperUrl}/docs/{$changelogHistory->slug}",
                'description' => mb_strlen($changelogHistory->details) > 240 ? mb_substr(
                    $changelogHistory->details,
                    0,
                    240
                ) . '...' : $changelogHistory->details,
                'pubDate'     => new DateTime($changelogHistory->changelogDate)->format(DateTimeInterface::RSS),
                'guid'        => $changelogHistory->id,
            ], $changelogHistories),
        ]);

        file_put_contents($this->publicDirectory . '/rss/mobile-sdks.xml', $rss);
    }
}
