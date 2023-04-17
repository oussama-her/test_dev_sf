<?php

namespace App\Controller;

use App\Service\ImagesCollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ImagesCollectionService
     */
    private $imagesCollectionService;

    /**
     * @param ImagesCollectionService $imagesCollectionService
     */
    public function __construct(ImagesCollectionService $imagesCollectionService)
    {
        $this->imagesCollectionService = $imagesCollectionService;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $this->imagesCollectionService->storeImagesFromRssSource();
        $this->imagesCollectionService->storeImagesFromApiSource();

        return $this->render('default/index.html.twig', [
            'images' => $this->imagesCollectionService->getImages(),
        ]);
    }
}
