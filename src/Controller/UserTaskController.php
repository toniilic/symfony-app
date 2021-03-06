<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskApplication;
use App\Form\TaskType;
use App\Repository\TaskApplicationRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/task")
 */
class UserTaskController extends AbstractController
{
    /**
     * @Route("/", name="user_task_index", methods={"GET"})
     */
    public function index(TaskRepository $taskRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $tasks = $taskRepository->findTasksByUser($this->getUser());
        
        return $this->render('user_task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/new", name="user_task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('user_task_index');
        }


        return $this->render('user_task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('user_task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $user = $this->getUser();

        $task->setLocation($user->getLocation());

        $form = $this->createForm(TaskType::class, $task, array('attr' => ['user' => $user]));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_task_index', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('user_task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_task_index');
    }

    /**
     * @Route("/task-applications/{id}", name="user_task_task_applications", methods={"GET"})
     */
    public function showFromTaskAndUser(TaskApplicationRepository $taskAppRepo, Task $task): Response
    {
        $taskApplications = $taskAppRepo->findTaskApplicationsAndUsersByTask($task);
        
        dump($taskApplications[0]->getUser()[0]);

        return $this->render('user_task/task_applications.html.twig', [
            'taskApplications' => $taskApplications,
            'task' => $task
        ]);
    }


}
