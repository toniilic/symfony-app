<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Location;
use App\Entity\PhoneNumber;
use App\Entity\Task;
use App\Entity\TaskApplication;
use App\Entity\User;
use App\Form\TaskType;
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
use Symfony\Component\HttpFoundation\Response;
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
        //$task->setLocation($user->getLocations());

        $form = $this->createForm(TaskType::class, $task, array('attr' => ['user' => $user]));

        /*$form = $this->createFormBuilder($task)
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
            ->getForm();*/

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

            $this->addFlash('warning', 'Zadatak napravljen! Molimo pričekajte na odobravanje.');

            return $this->redirectToRoute('user_task_index');
        }

        dump($user);

        return $this->render('task/create.html.twig', array(
            'form' => $form->createView(),
            //'location' => $user->getLocation()
        ));
    }

    /**
     * @Route("/show/{id}", name="task_show")
     */
    public function show(Task $task)
    {
        if(!$task->getApproved()) {
            $this->addFlash('danger', 'Zadatak još nije odobren.');

            return $this->redirectToRoute('home');
        }



        $user = $this->getUser();

        $is_owner = $user == $task->getUser();

        // get users task application for this task
        $location = $this->getDoctrine()
            ->getRepository(Location::class)
            ->findLocationByUser($task->getUser());

        // get users task application for this task
        $taskRepo = $this->getDoctrine()
            ->getRepository(TaskApplication::class);
        $taskApplications = $taskRepo
            ->findTaskApplicationsByUserAndTask($user, $task);
        // TODO: get current user application for this tasks
        $currentUserAlredySubmitted = count($taskApplications) !== 0 ? true : false;

        $taskApplicationCount = count($taskRepo->findTaskApplicationsByTask($task));

        return $this->render('task/show.html.twig', [
            'task' => $task,
            'category' => $task->getCategory(),
            'phoneNumber' => $task->getPhoneNumber(),
            'is_owner' => $is_owner,
            //'taskApplication' => $taskApplication,
            'location' => $location,
            'taskApplicationCount' => $taskApplicationCount,
            'currentUserAlredySubmitted' => $currentUserAlredySubmitted,
            'currentUserSubmission' => isset($taskApplications[0]) ? $taskApplications[0] : null

        ]);
    }

/*    /**
     * @Route("/search", methods={"GET"}, name="task_search")
     */
/*    public function search(Request $request, TaskRepository $tasks): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render('task/search.html.twig');
        }
        $query = $request->query->get('q', '');
        $limit = $request->query->get('l', 10);
        $foundTasks = $tasks->findBySearchQuery($query, $limit);
        $results = [];
        foreach ($foundTasks as $task) {
            $results[] = [
                'title' => htmlspecialchars($task->getTitle(), ENT_COMPAT | ENT_HTML5),
                'date' => $task->getPublishedAt()->format('M d, Y'),
                'author' => htmlspecialchars($task->getAuthor()->getFullName(), ENT_COMPAT | ENT_HTML5),
                'summary' => htmlspecialchars($task->getSummary(), ENT_COMPAT | ENT_HTML5),
                'url' => $this->generateUrl('task_show', ['slug' => $task->getId()]),
            ];
        }
        return $this->json($results);
    }*/
}
