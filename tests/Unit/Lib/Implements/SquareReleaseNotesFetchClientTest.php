<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\Implements;

use App\Lib\ChangelogHistory;
use App\Lib\Implements\SquareReleaseNotesFetchClient;
use App\Tests\Fixtures\ChangelogHistoryFixtures;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SquareReleaseNotesFetchClientTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private DenormalizerInterface $denormalizer;
    private SquareReleaseNotesFetchClient $fetchClient;
    private string $squareDeveloperUrl = 'https://developer.squareup.com';

    protected function setUp(): void
    {
        // Create mock objects
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);

        // Create the fetch client
        $this->fetchClient = new SquareReleaseNotesFetchClient(
            $this->httpClient,
            $this->squareDeveloperUrl,
            $this->denormalizer
        );
    }

    public function testFetchWebPaymentsSDKChangelogHistoryList(): void
    {
        $htmlContent = ChangelogHistoryFixtures::getSampleHtmlContent();
        $expectedChangelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();

        // Create a mock response
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($htmlContent);

        // Configure the HTTP client mock
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $this->squareDeveloperUrl . '/docs/changelog/webpaymentsdk')
            ->willReturn($response);

        // Extract the expected JSON data from the HTML
        $crawler = new Crawler($htmlContent);
        $json = $crawler->filterXPath('//script[@id="__NEXT_DATA__"]');
        $data = json_decode($json->text(), true);
        $changelogData = $data['props']['pageProps']['data']['doc']['pageInView']['page']['changelogHistory'];

        // Configure the denormalizer mock
        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($changelogData, ChangelogHistory::class . '[]')
            ->willReturn($expectedChangelogHistories);

        // Fetch the changelog histories
        $changelogHistories = $this->fetchClient->fetchWebPaymentsSDKChangelogHistoryList();

        // Verify the sorting (should be by date in descending order)
        $this->assertEquals('2023-07-30', $changelogHistories[0]->changelogDate);
        $this->assertEquals('2023-06-15', $changelogHistories[1]->changelogDate);
        $this->assertEquals('2023-05-01', $changelogHistories[2]->changelogDate);
    }

    public function testFetchSquareAPIsAndSDKsChangelogHistoryList(): void
    {
        $htmlContent = ChangelogHistoryFixtures::getSampleHtmlContent();
        $expectedChangelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();

        // Create a mock response
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($htmlContent);

        // Configure the HTTP client mock
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $this->squareDeveloperUrl . '/docs/changelog/connect')
            ->willReturn($response);

        // Extract the expected JSON data from the HTML
        $crawler = new Crawler($htmlContent);
        $json = $crawler->filterXPath('//script[@id="__NEXT_DATA__"]');
        $data = json_decode($json->text(), true);
        $changelogData = $data['props']['pageProps']['data']['doc']['pageInView']['page']['changelogHistory'];

        // Configure the denormalizer mock
        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($changelogData, ChangelogHistory::class . '[]')
            ->willReturn($expectedChangelogHistories);

        // Fetch the changelog histories
        $changelogHistories = $this->fetchClient->fetchSquareAPIsAndSDKsChangelogHistoryList();

        // Verify the sorting (should be by date in descending order)
        $this->assertEquals('2023-07-30', $changelogHistories[0]->changelogDate);
        $this->assertEquals('2023-06-15', $changelogHistories[1]->changelogDate);
        $this->assertEquals('2023-05-01', $changelogHistories[2]->changelogDate);
    }

    public function testFetchMobileSDKsChangelogHistoryList(): void
    {
        $htmlContent = ChangelogHistoryFixtures::getSampleHtmlContent();
        $expectedChangelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();

        // Create a mock response
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn($htmlContent);

        // Configure the HTTP client mock
        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $this->squareDeveloperUrl . '/docs/changelog/mobile')
            ->willReturn($response);

        // Extract the expected JSON data from the HTML
        $crawler = new Crawler($htmlContent);
        $json = $crawler->filterXPath('//script[@id="__NEXT_DATA__"]');
        $data = json_decode($json->text(), true);
        $changelogData = $data['props']['pageProps']['data']['doc']['pageInView']['page']['changelogHistory'];

        // Configure the denormalizer mock
        $this->denormalizer->expects($this->once())
            ->method('denormalize')
            ->with($changelogData, ChangelogHistory::class . '[]')
            ->willReturn($expectedChangelogHistories);

        // Fetch the changelog histories
        $changelogHistories = $this->fetchClient->fetchMobileSDKsChangelogHistoryList();

        // Verify the sorting (should be by date in descending order)
        $this->assertEquals('2023-07-30', $changelogHistories[0]->changelogDate);
        $this->assertEquals('2023-06-15', $changelogHistories[1]->changelogDate);
        $this->assertEquals('2023-05-01', $changelogHistories[2]->changelogDate);
    }
}
