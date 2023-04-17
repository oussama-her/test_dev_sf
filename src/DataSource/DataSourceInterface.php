<?php

namespace App\DataSource;

use Doctrine\Common\Collections\ArrayCollection;

interface DataSourceInterface
{
    /**
     * @param ArrayCollection $imageUrlsStore
     * @return ArrayCollection
     */
    public function getData(ArrayCollection $imageUrlsStore): ArrayCollection;
}
