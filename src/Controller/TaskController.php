<?php

namespace App\Controller;

use App\Service\PosaoHrScraper;
use App\Service\PosaoUrlScraper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class TaskController extends AbstractController
{
    /**
    * @Route("/create", name="task_create")
    */
    public function index()
    {


        // The second parameter is used to specify on what object the role is tested.
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('task/create.html.twig');
    }

    /**
     * @Route("/show_task", name="task_show")
     */
    public function show()
    {
        return $this->render('task/show.html.twig');
    }
}