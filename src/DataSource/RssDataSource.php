<?php

namespace App\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RssDataSource implements DataSourceInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $rssUrl;
    /**
     * @var string
     */
    private $imageSrcHint;
    /**
     * @var array
     */
    private $links;

    public function __construct(HttpClientInterface $httpClient, array $rssParams)
    {
        $this->httpClient = $httpClient;
        $this->rssUrl = $rssParams['rssUrl'];
        $this->imageSrcHint = $rssParams['imageSrcHint'];
        $this->links = [];
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getData(ArrayCollection $imageUrlsStore): ArrayCollection
    {
        $this->storeLinksFromRssSource($this->rssUrl);

        if (!empty($this->links)) {
            foreach ($this->links as $link) {
                $imageSource = $this->findFirstImageByHintFromUrl($link, $this->imageSrcHint);

                if (!empty($imageSource) && !$imageUrlsStore->contains($imageSource)) {
                    $imageUrlsStore->add($imageSource);
                }
            }
        }

        return $imageUrlsStore;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function storeLinksFromRssSource(string $rssUrl)
    {
        $response = $this->httpClient->request('GET', $rssUrl);

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $crawler = new Crawler();
            $crawler->addXmlContent($response->getContent());
            $this->links = $crawler->filter('item > link')->each(function (Crawler $node) {
                return $node->text();
            });
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function findFirstImageByHintFromUrl(string $link, string $imageSrcHint): ?string
    {
        $imageUrl = '';
        $response = $this->httpClient->request('GET', $link);

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $crawler = new Crawler($response->getContent());
            $imageTag = $crawler->filterXPath('//img[contains(@src, "'.$imageSrcHint.'")][1]');
            $imageUrl = $imageTag->attr('src');
        }

        return $imageUrl;
    }

    public function getLinks(): array
    {
        return $this->links;
    }
}
