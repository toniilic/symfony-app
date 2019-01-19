<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModeratorController extends Controller
{
    /**
     * @Route("/moderator", name="moderator")
     */
    public function index(Request $request)
    {
        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Tasks entity
        $taskRepository = $em->getRepository(Task::class);

        // Find all the data on the Tasks table, filter your query as you need
        $allTasksQuery = $taskRepository->createQueryBuilder('p')->getQuery();

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

        dump($tasks);

        return $this->render('moderator/index.html.twig', [
            'controller_name' => 'ModeratorController',
            'tasks' => $tasks,
        ]);
    }
}
