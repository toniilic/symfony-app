<?php

namespace App\Controller;

use App\Entity\TaskApplication;
use App\Form\TaskApplicationType;
use App\Repository\TaskApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/task/application")
 */
class UserTaskApplicationController extends AbstractController
{
    /**
     * @Route("/", name="task_application_index", methods={"GET"})
     */
    public function index(TaskApplicationRepository $taskApplicationRepository): Response
    {
        $user = $this->getUser();
        $taskApplications = $taskApplicationRepository->findTaskApplicationsByUser($user);

        $taskApplicationsByUser = [];
        foreach($taskApplications as $taskApplication) {
            // TODO: refactor
            $taskApplications = $taskApplication->getUser()->getValues();

            foreach($taskApplications as $taskApplicationUser) {
                if($taskApplicationUser == $user) {
                    $taskApplicationsByUser[] = $taskApplication;
                }
            }
        }

        return $this->render('user_task_application/index.html.twig', [
            'task_applications' => $taskApplicationsByUser,
        ]);
    }

    /**
     * @Route("/new", name="task_application_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $taskApplication = new TaskApplication();
        $form = $this->createForm(TaskApplicationType::class, $taskApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taskApplication);
            $entityManager->flush();

            return $this->redirectToRoute('task_application_index');
        }

        return $this->render('user_task_application/new.html.twig', [
            'task_application' => $taskApplication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_application_show", methods={"GET"})
     */
    public function show(TaskApplication $taskApplication): Response
    {
        return $this->render('user_task_application/show.html.twig', [
            'task_application' => $taskApplication,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_application_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TaskApplication $taskApplication): Response
    {
        $form = $this->createForm(TaskApplicationType::class, $taskApplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_application_index', [
                'id' => $taskApplication->getId(),
            ]);
        }

        return $this->render('user_task_application/edit.html.twig', [
            'task_application' => $taskApplication,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_application_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TaskApplication $taskApplication): Response
    {
        if ($this->isCsrfTokenValid('delete'.$taskApplication->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taskApplication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_application_index');
    }


}
