<?php
declare(strict_types=1);

namespace App\Lib;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function json_decode;

final readonly class SquareReleaseNotesFetchClient implements SquareReleaseNotesFetchClientInterface
{
    public function __construct(
        private HttpClientInterface   $httpClient,
        private string                $squareDeveloperUrl,
        private DenormalizerInterface $denormalizer,
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

        // extract changelog history
        $json = $crawler->filterXPath('//script[@id="__NEXT_DATA__"]');
        $d = json_decode($json->text(), true);
        $changelogHistory = $d['props']['pageProps']['data']['doc']['pageInView']['page']['changelogHistory'] ?? [];

        return $this->denormalizer->denormalize($changelogHistory, ChangelogHistory::class . '[]');
    }
}
