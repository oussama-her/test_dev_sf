<?php

namespace App\Tests\DataSource;

use App\DataSource\HttpClientInterface;
use App\DataSource\RssDataSource;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RssDataSourceTest extends TestCase
{
    private $httpClientMock;
    private $responseMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }

    public function testStoreLinksFromRssSource()
    {
        $rssUrl = 'https://example.com/feed';
        $imageSrcHint = 'example.com/image';

        $rssSource = <<<'XML'
            <rss version="2.0">
                <channel>
                    <item>
                        <link>https://example.com/article1</link>
                    </item>
                    <item>
                        <link>https://example.com/article2</link>
                    </item>
                </channel>
            </rss>
        XML;

        $this->responseMock->method('getStatusCode')
            ->willReturn(200);
        $this->responseMock->method('getContent')
            ->willReturn($rssSource);
        $this->httpClientMock->method('request')
            ->with('GET', $rssUrl)
            ->willReturn($this->responseMock);

        $rssDataSource = new RssDataSource($this->httpClientMock, [
            'rssUrl' => $rssUrl,
            'imageSrcHint' => $imageSrcHint,
        ]);

        $rssDataSource->storeLinksFromRssSource($rssUrl);
        $this->assertCount(2, $rssDataSource->getLinks());
    }

    public function testFindFirstImageByHintFromUrl()
    {
        $link = 'https://example.com/article';
        $imageSrcHint = 'example.com/image';

        $html = <<<'HTML'
            <html>
                <head>
                    <title>Example</title>
                </head>
                <body>
                    <img src="https://example.com/image1.png">
                    <img src="https://example.com/image2.png">
                    <img src="https://example.com/image3.png">
                </body>
            </html>
        HTML;

        $this->responseMock->method('getStatusCode')
            ->willReturn(200);
        $this->responseMock->method('getContent')
            ->willReturn($html);
        $this->httpClientMock->method('request')
            ->with('GET', $link)
            ->willReturn($this->responseMock);

        $rssDataSource = new RssDataSource($this->httpClientMock, [
            'rssUrl' => 'https://example.com/feed',
            'imageSrcHint' => $imageSrcHint,
        ]);

        $imageUrl = $rssDataSource->findFirstImageByHintFromUrl($link, $imageSrcHint);
        $this->assertEquals('https://example.com/image1.png', $imageUrl);
    }
}
