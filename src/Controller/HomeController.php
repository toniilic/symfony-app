<?php

namespace App\Controller;

use App\Entity\Task;
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
        $repository = $this->getDoctrine()->getRepository(Task::class);

        $tasks = $repository->findByExampleField();

        dump($tasks);

        return $this->render('home/index.html.twig', array(
            'tasks' => $tasks
        ));
    }

    /**
     * @Route("/show", name="show")
     */
    public function show()
    {
        return $this->render('home/show.html.twig');
    }
}