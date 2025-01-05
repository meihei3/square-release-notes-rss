<?php
declare(strict_types=1);

namespace App\Lib;

use Symfony\Component\DomCrawler\Crawler;

interface DOMNodeCleanerInterface
{
    public function cleanUpHTMLText(Crawler $crawler): string;
}
