<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\Implements;

use App\Lib\Implements\ChangelogHistoryRSSBuilder;
use App\Tests\Fixtures\ChangelogHistoryFixtures;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\ClockInterface;
use Twig\Environment;

class ChangelogHistoryRSSBuilderTest extends TestCase
{
    private string $tempDir;
    private Environment $twig;
    private ClockInterface $clock;
    private ChangelogHistoryRSSBuilder $rssBuilder;
    private string $squareDeveloperUrl = 'https://developer.squareup.com';

    protected function setUp(): void
    {
        // Create a temporary directory for test files
        $this->tempDir = sys_get_temp_dir() . '/square-release-notes-test-' . uniqid();
        mkdir($this->tempDir . '/rss', 0777, true);

        // Create a mock Twig environment
        $this->twig = $this->createMock(Environment::class);

        // Create a mock clock
        $this->clock = $this->createMock(ClockInterface::class);
        $now = new DateTimeImmutable('2023-08-01T12:00:00Z');
        $this->clock->method('now')->willReturn($now);

        // Create the RSS builder with the temp directory
        $this->rssBuilder = new ChangelogHistoryRSSBuilder(
            $this->tempDir,
            $this->twig,
            $this->squareDeveloperUrl,
            $this->clock
        );
    }

    protected function tearDown(): void
    {
        // Clean up temporary files
        if (file_exists($this->tempDir . '/rss/webpaymentsdk.xml')) {
            unlink($this->tempDir . '/rss/webpaymentsdk.xml');
        }
        if (file_exists($this->tempDir . '/rss/square-apis-and-sdks.xml')) {
            unlink($this->tempDir . '/rss/square-apis-and-sdks.xml');
        }
        if (file_exists($this->tempDir . '/rss/mobile-sdks.xml')) {
            unlink($this->tempDir . '/rss/mobile-sdks.xml');
        }
        if (file_exists($this->tempDir . '/rss/paymentform.xml')) {
            unlink($this->tempDir . '/rss/paymentform.xml');
        }
        if (file_exists($this->tempDir . '/rss/requirements.xml')) {
            unlink($this->tempDir . '/rss/requirements.xml');
        }

        // Remove the temp directory
        rmdir($this->tempDir . '/rss');
        rmdir($this->tempDir);
    }

    public function testBuildWebPaymentsSDK(): void
    {
        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $expectedRssContent = '<rss version="2.0">Sample RSS content</rss>';

        // Configure the expected Twig render call
        $this->twig->expects($this->once())
            ->method('render')
            ->with('connect.xml.twig', $this->callback(function ($params) {
                // Verify the parameters passed to the template
                $this->assertEquals('Release Notes: Square Web Payments SDK', $params['title']);
                $this->assertEquals("{$this->squareDeveloperUrl}/docs/changelog/webpaymentsdk", $params['link']);
                $this->assertEquals('Release notes for Square Web Payments SDK.', $params['description']);
                $this->assertEquals($this->clock->now()->format(DateTimeInterface::RSS), $params['pubDate']);

                // Verify the items
                $this->assertCount(3, $params['items']);

                // Check the first item
                $item = $params['items'][0];
                $this->assertEquals('Web Payments SDK 1.0.0 released', $item['title']);
                $this->assertEquals("{$this->squareDeveloperUrl}/docs/changelog/webpaymentsdk/2023-05-01", $item['link']);
                $this->assertEquals('Initial release of the Web Payments SDK with support for credit card payments.', $item['description']);
                $this->assertEquals('changelog-1', $item['guid']);

                // In our test data, none of the descriptions are long enough to be truncated
                // Verify that all descriptions are properly handled
                foreach ($params['items'] as $item) {
                    if (mb_strlen($item['description']) > 240) {
                        // If there is a long description, it should be truncated
                        $this->assertTrue(mb_strlen($item['description']) <= 243); // 240 + '...'
                        $this->assertStringEndsWith('...', $item['description']);
                    } else {
                        // If the description is not long, it should not be truncated
                        $this->assertStringNotContainsString('...', $item['description']);
                    }
                }

                return true;
            }))
            ->willReturn($expectedRssContent);

        // Build the RSS
        $this->rssBuilder->buildWebPaymentsSDK($changelogHistories);

        // Verify the file was created
        $this->assertFileExists($this->tempDir . '/rss/webpaymentsdk.xml');

        // Verify the file content
        $this->assertEquals($expectedRssContent, file_get_contents($this->tempDir . '/rss/webpaymentsdk.xml'));
    }

    public function testBuildSquareAPIsAndSDKs(): void
    {
        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $expectedRssContent = '<rss version="2.0">Sample RSS content</rss>';

        // Configure the expected Twig render call
        $this->twig->expects($this->once())
            ->method('render')
            ->with('connect.xml.twig', $this->callback(function ($params) {
                // Verify the parameters passed to the template
                $this->assertEquals('Release Notes: Square APIs and SDKs', $params['title']);
                $this->assertEquals("{$this->squareDeveloperUrl}/docs/changelog/connect", $params['link']);
                $this->assertEquals('Release notes for Square APIs and SDKs.', $params['description']);

                return true;
            }))
            ->willReturn($expectedRssContent);

        // Build the RSS
        $this->rssBuilder->buildSquareAPIsAndSDKs($changelogHistories);

        // Verify the file was created
        $this->assertFileExists($this->tempDir . '/rss/square-apis-and-sdks.xml');

        // Verify the file content
        $this->assertEquals($expectedRssContent, file_get_contents($this->tempDir . '/rss/square-apis-and-sdks.xml'));
    }

    public function testBuildMobileSDKs(): void
    {
        $changelogHistories = ChangelogHistoryFixtures::createSampleChangelogHistories();
        $expectedRssContent = '<rss version="2.0">Sample RSS content</rss>';

        // Configure the expected Twig render call
        $this->twig->expects($this->once())
            ->method('render')
            ->with('connect.xml.twig', $this->callback(function ($params) {
                // Verify the parameters passed to the template
                $this->assertEquals('Release Notes: Square Mobile SDKs', $params['title']);
                $this->assertEquals("{$this->squareDeveloperUrl}/docs/changelog/mobile", $params['link']);
                $this->assertEquals('Release notes for Square Mobile SDKs.', $params['description']);

                return true;
            }))
            ->willReturn($expectedRssContent);

        // Build the RSS
        $this->rssBuilder->buildMobileSDKs($changelogHistories);

        // Verify the file was created
        $this->assertFileExists($this->tempDir . '/rss/mobile-sdks.xml');

        // Verify the file content
        $this->assertEquals($expectedRssContent, file_get_contents($this->tempDir . '/rss/mobile-sdks.xml'));
    }
}
