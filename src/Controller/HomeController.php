<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
    * @Route("/", name="home")
    */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
/*        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('toniilicdeveloper@gmail.com')
            ->setTo('toniilicdeveloper@gmail.com')
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => 'Toni']
                ),
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        /*;

        $mailer->send($message);*/



        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Tasks entity
        $taskRepository = $em->getRepository(Task::class);

        // Find all the data on the Tasks table, filter your query as you need
        $allTasksQuery = $taskRepository->createQueryBuilder('p')
            ->where('p.approved != :approved')
            ->setParameter('approved', 'false')
            ->orderBy('p.publishedAt', 'DESC')
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
            5
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