<?php

namespace App\Controller;

use App\Service\PosaoHrScraper;
use App\Service\PosaoUrlScraper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class HomeController extends AbstractController
{
    /**
    * @Route("/", name="home")
    */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/show", name="show")
     */
    public function show()
    {
        return $this->render('home/show.html.twig');
    }
}