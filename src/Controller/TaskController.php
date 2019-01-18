<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Location;
use App\Entity\PhoneNumber;
use App\Entity\Task;
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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    /**
    * @Route("/create", name="task_create")
    */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $task = new Task();
        $task->setUser($user);
        $task->setLocation($user->getLocation());

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
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
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

            return $this->redirectToRoute('home');
        }

        return $this->render('task/create.html.twig', array(
            'form' => $form->createView(),
            'location' => $user->getLocation()
        ));
    }

    /**
     * @Route("/show/{id}", name="task_show")
     */
    public function show(Task $task)
    {
        $user = $this->getUser();

        $is_owner = $user == $task->getUser();

        // get users task application for this task
        $location = $this->getDoctrine()
            ->getRepository(Location::class)
            ->findLocationByUser($task->getUser());

        // get users task application for this task
        $taskApplication = $this->getDoctrine()
            ->getRepository(TaskApplication::class)
            ->findTaskApplicationByTaskAndSubmitter($task, $user);

        dump($taskApplication);

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'is_owner' => $is_owner,
            'taskApplication' => $taskApplication,
            'location' => $location
        ]);
    }
}