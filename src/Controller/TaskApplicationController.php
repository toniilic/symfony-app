<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskApplication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskApplicationController extends AbstractController
{
    /**
     * @Route("/task/{id}/application", name="task_application")
     */
    public function apply(Request $request, Task $task)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $taskApplication = new TaskApplication();
        $taskApplication->setTask($task);

        $form = $this->createFormBuilder($taskApplication)
            ->add('hourlyRate', IntegerType::class)
            ->add('coverLetter', TextareaType::class)
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $taskApplication = $form->getData();
            $taskApplication->addUser($user);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $user->addTaskApplication($taskApplication);
            $entityManager->persist($taskApplication);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }


        return $this->render('task_application/apply.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }
}
