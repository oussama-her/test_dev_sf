<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;

interface ImagesCollectionInterface
{
    /**
     * @return ArrayCollection
     */
    public function getImages(): ArrayCollection;

    /**
     * @return void
     */
    public function storeImagesFromRssSource(): void;

    /**
     * @return void
     */
    public function storeImagesFromApiSource(): void;
}
