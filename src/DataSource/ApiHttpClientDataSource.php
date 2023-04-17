<?php

namespace App\DataSource;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiHttpClientDataSource implements DataSourceInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var array
     */
    private $links;

    public function __construct(HttpClientInterface $httpClient, array $apiParams)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiParams['apiUrl'];
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
        $this->storeLinksFromApiSource($this->apiUrl);

        if (!empty($this->links)) {
            foreach ($this->links as $link) {
                $imageSource = $this->findFirstImageFromUrl($link);

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
    public function storeLinksFromApiSource(string $apiUrl)
    {
        $response = $this->httpClient->request('GET', $apiUrl);

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $data = json_decode($response->getContent(), true);
            $articles = new ArrayCollection($data['articles']);
            $this->links = $articles->filter(function ($article) {
                return !empty($article['urlToImage']);
            })->map(function ($article) {
                return $article['urlToImage'];
            });
        }
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function findFirstImageFromUrl(string $link): ?string
    {
        $imageUrl = '';
        $response = $this->httpClient->request('GET', $link);

        if (Response::HTTP_OK === $response->getStatusCode()) {
            $crawler = new Crawler($response->getContent());
            $imageTag = $crawler->filter('image')->first();

            if ($imageTag->count() > 0 && !empty($imageTag->attr('src'))) {
                $imageUrl = $imageTag->attr('src');
            }
        }

        return $imageUrl;
    }
}
