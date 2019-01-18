<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class HomeController extends Controller
{
    /**
    * @Route("/", name="home")
    */
    public function index(Request $request)
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Tasks entity
        $taskRepository = $em->getRepository(Task::class);

        // Find all the data on the Tasks table, filter your query as you need
        $allTasksQuery = $taskRepository->createQueryBuilder('p')
            ->where('p.approved != :approved')
            ->setParameter('approved', 'false')
            ->getQuery();

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator  = $this->get('knp_paginator');

        // Paginate the results of the query
        $tasks = $paginator->paginate(
        // Doctrine Query, not results
            $allTasksQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );






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