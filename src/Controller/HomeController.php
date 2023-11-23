<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(AdvertRepository $advertRepository): Response
    {
        $publishedAdverts  = $advertRepository->findAllPublished();

        return $this->render('home/index.html.twig', [
            'publishedAdverts' => $publishedAdverts,
        ]);
    }
}
