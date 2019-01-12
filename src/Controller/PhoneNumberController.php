<?php

namespace App\Controller;

use App\Entity\PhoneNumber;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/phone")
 */
class PhoneNumberController extends AbstractController
{
    /**
    * @Route("/create", name="phone_number_create")
    */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $phoneNumber = new PhoneNumber();
        $phoneNumber->setUser($user);

        $form = $this->createFormBuilder($phoneNumber)
            ->add('number', IntegerType::class)
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Mobile' => 'mobile',
                    'Home' => 'home'
                ),
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Location'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $phoneNumber = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($phoneNumber);
             $entityManager->flush();

             dump('redirect to home');

            return $this->redirectToRoute('home');
        }

        return $this->render('location/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

}