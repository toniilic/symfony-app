<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Location;
use App\Entity\PhoneNumber;
use App\Entity\Task;
use App\Entity\TaskApplication;
use App\Entity\User;
use App\Repository\TaskRepository;
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
        $taskApplications = $this->getDoctrine()
            ->getRepository(TaskApplication::class)
            ->findTaskApplicationsByTask($task);
        // TODO: get current user application for this tasks
        $currentUserAlredySubmitted = false;
        $currentUserSubmission = null;

        foreach($taskApplications as $taskApplication) {

            $taskApplications = $taskApplication->getUser()->getValues();

            foreach($taskApplications as $taskApplicationUser) {
                if($taskApplicationUser == $user) {
                    $currentUserAlredySubmitted = true;
                    $currentUserSubmission = $taskApplication;
                }
            }
        }

        $taskApplicationRepo = $this->getDoctrine()
            ->getRepository(TaskApplication::class);
        $taskApplicationCount = $taskApplicationRepo->getTaskApplicationsCount($task);

        /** @var TaskRepository $taskRepo */
        $taskRepo = $this->getDoctrine()
            ->getRepository(Task::class);

        /** @var @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()
            ->getRepository(User::class);

        $em = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['id'=>1]);

        /*dump($task);
        dump($task->getUser()->getId());
        dump($userRepo->getById($task->getUser()->getId()));
        dump($this->getUser());
        dump($task->getCategory());
        dump($task->getCategory()->getTasks());
        dump($task->getPhoneNumber());*/
        /*dump($taskApplication);*/
        // TODO: get Task application by current user

        /**
         * TODO: show category,
         */

        dump($currentUserAlredySubmitted);
        dump($currentUserSubmission);

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'category' => $task->getCategory(),
            'phoneNumber' => $task->getPhoneNumber(),
            'is_owner' => $is_owner,
            //'taskApplication' => $taskApplication,
            'location' => $location,
            'taskApplicationCount' => $taskApplicationCount,
            'currentUserAlredySubmitted' => $currentUserAlredySubmitted,
            'currentUserSubmission' => $currentUserSubmission

        ]);
    }
}
