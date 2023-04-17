<?php

namespace App\Service;

use App\DataSource\ApiHttpClientDataSource;
use App\DataSource\RssDataSource;
use App\DataSource\SymfonyHttpClient;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ImagesCollectionService implements ImagesCollectionInterface
{
    /**
     * @var ArrayCollection
     */
    private $images;
    /**
     * @var SymfonyHttpClient
     */
    private $httpClient;
    /**
     * @var array
     */
    private $rssParams;
    /**
     * @var array
     */
    private $apiParams;

    public function __construct(SymfonyHttpClient $httpClient, array $rssParams, array $apiParams)
    {
        $this->images = new ArrayCollection();
        $this->httpClient = $httpClient;
        $this->rssParams = $rssParams;
        $this->apiParams = $apiParams;
    }

    public function getImages(): ArrayCollection
    {
        return $this->images;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface
     */
    public function storeImagesFromRssSource(): void
    {
        $this->images = (new RssDataSource($this->httpClient, $this->rssParams))->getData($this->images);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface
     */
    public function storeImagesFromApiSource(): void
    {
        $this->images = (new ApiHttpClientDataSource($this->httpClient, $this->apiParams))->getData($this->images);
    }
}
