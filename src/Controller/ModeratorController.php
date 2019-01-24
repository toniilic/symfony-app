<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Category;
use App\Entity\Location;
use App\Entity\PhoneNumber;
use App\Entity\TaskApplication;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;
use IntlDateFormatter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $allTasksQuery = $taskRepository->createQueryBuilder('p')->orderBy('p.id', 'DESC')->getQuery();

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

    /**
     * @Route("/moderator/edit/{id}", name="moderator_edit")
     */
    public function create(Request $request, Task $task)
    {
        $user = $this->getUser();

        $form = $this->createFormBuilder($task)
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
            ))
            ->add('phoneNumber', EntityType::class, array(
                'class' => PhoneNumber::class,
                'query_builder' => function (EntityRepository $er) use($user){
                    return $er->createQueryBuilder('p')
                        ->where('p.isHidden != true')
                        ->andWhere('p.user = :user')
                        ->setParameter('user', $user)
                        ->orderBy('p.number', 'ASC');
                },
                'choice_label' => 'number',
            ))
            ->add('levelOfExpertise', ChoiceType::class, array(
                'choices'  => array(
                    'Novice' => 'Novice',
                    'Experienced' => 'Experienced',
                    'Expert' => 'Expert',
                ),
            ))
            ->add('budget', IntegerType::class)
            ->add('duration', IntegerType::class)
            ->add('dueDate', DateTimeType::class, array(
                'years' => range(date('Y'), date('Y')+2)
            ))
            ->add('approved', ChoiceType::class, array(
                'choices'  => array(
                    'Approved' => true,
                    'Not approved' => false,
                ),
            ))
            ->add('save', SubmitType::class, array('label' => 'Update Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Task updated.'
            );

            return $this->redirectToRoute('moderator');
        }

        return $this->render('moderator/edit.html.twig', array(
            'form' => $form->createView(),
            'location' => $user->getLocation()
        ));
    }
}
