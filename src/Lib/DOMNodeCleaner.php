<?php

namespace App\Lib;

use DOMDocument;
use DOMElement;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use function preg_match;

class DOMNodeCleaner implements DOMNodeCleanerInterface
{
    public function cleanUpHTMLText(Crawler $crawler): string {
        $dom = new DOMDocument();
        if (($htmlSnippet = $crawler->html()) === '') {
            return '';
        }

        $dom->loadHTML($htmlSnippet, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);

        $this->removeAttributesRecursively($dom);
        // 本当は不要なタグの除去も行いたい

        return $dom->saveHTML();
    }

    private function removeAttributesRecursively(DOMNode $node): void {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            /** @var DOMElement $element */
            $element = $node;
            if ($element->hasAttributes()) {
                for ($i = $element->attributes->length - 1; $i >= 0; $i--) {
                    if (($attrNode = $element->attributes->item($i)) === null) {
                        continue;
                    }
                    if (preg_match('/\A(class|data-)/', $attrNode->nodeName) === 1) {
                        $element->removeAttribute($attrNode->nodeName);
                    }
                }
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                $this->removeAttributesRecursively($childNode);
            }
        }
    }
}
