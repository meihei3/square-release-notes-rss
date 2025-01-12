<?php
declare(strict_types=1);

namespace App\Lib\Implements;

use App\Lib\ChangelogHistoryRSSBuilderInterface;
use DateTime;
use DateTimeInterface;
use Twig\Environment;
use function array_map;
use function file_put_contents;
use function mb_strlen;
use function mb_substr;

final readonly class ChangelogHistoryRSSBuilder implements ChangelogHistoryRSSBuilderInterface
{
    public function __construct(
        private string      $publicDirectory,
        private Environment $twig,
        private string      $squareDeveloperUrl,
    ) {}

    /**
     * @inheritDoc
     */
    public function buildSquareAPIsAndSDKs(array $changelogHistories): void {
        $now = new DateTime();

        $rss = $this->twig->render('connect.xml.twig', [
            'title'       => 'Release Notes: Square APIs and SDKs',
            'link'        => "{$this->squareDeveloperUrl}/docs/changelog/connect",
            'description' => 'Release notes for Square APIs and SDKs.',
            'pubDate'     => $now->format(DateTimeInterface::RSS),
            'items'       => array_map(fn($changelogHistory) => [
                'title'       => $changelogHistory->summary,
                'link'        => "{$this->squareDeveloperUrl}/docs/{$changelogHistory->slug}",
                'description' => mb_strlen($changelogHistory->details) > 240 ? mb_substr($changelogHistory->details, 0, 240) . '...' : $changelogHistory->details,
                'pubDate'     => new DateTime($changelogHistory->changelogDate)->format(DateTimeInterface::RSS),
                'guid'        => $changelogHistory->id,
            ], $changelogHistories),
        ]);

        file_put_contents($this->publicDirectory . '/square-apis-and-sdks.xml', $rss);
    }
}
