<?php
declare(strict_types=1);

namespace App\Lib;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class SquareReleaseNotesFetchClient implements SquareReleaseNotesFetchClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string              $squareDeveloperUrl,
    ) {}

    /**
     * @inheritDoc
     */
    public function fetchSquareAPIsAndSDKsChangeLogList(): array {
        // retrieve Square API and SDKs changelog
        $response = $this->httpClient->request('GET', $this->squareDeveloperUrl . '/docs/changelog/connect');
        $html = $response->getContent();

        // parse changelog
        $crawler = new Crawler($html);
        $releases = $crawler->filter('[class*="ChangelogSetPage_wrap"]');
        $changelogs = $releases->each(function (Crawler $release) {
            $entry = $release->filter('a[data-tracking-id="changelog-entry-link"]')->first();
            $tags = $release->filter('[class*="ChangelogSetPage_tag-wrap"]')->children();

            return new Changelog(version: $entry->text(), url: $this->squareDeveloperUrl . $entry->attr('href'), tags: $tags->each(fn(
                    Crawler $tag
                ) => $tag->text()),);
        });

        return $changelogs;
    }
}
