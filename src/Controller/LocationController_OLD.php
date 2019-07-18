<?php

namespace App\Controller;

use App\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

/**
 * @Route("/location")
 */
class LocationController_OLD extends AbstractController
{
    /**
    * @Route("/create", name="location_create")
    */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();
        $locationRepository = $em->getRepository(Location::class);
        $location = $locationRepository->findLocationByUser($user);
        if($location) {
            $this->addFlash(
                'info',
                // TODO: ovdje greškom
                'Već imaš lokaciju! Možeš ju ažurirati u sučelju.'
            );

            return $this->redirectToRoute('home');
        }

        $location = new Location();
        $location->setCurrency('HRK');
        $location->setCountry('Hrvatska');
        $location->setUser($user);
        if(!$user) {
            throw \Exception('No user');
        }
/*        $location->setAddress();
        $location->setPostalCode();
        $location->setCity();
        $location->setRegion();*/

        $form = $this->createFormBuilder($location)
            ->add('address', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('city', ChoiceType::class, array(
                'choices'  => array(
                    'Rijeka' => 'Rijeka',
                    'Zagreb' => 'Zagreb',
                    'Pula' => 'Pula',
                ),
            ))
            ->add('region', ChoiceType::class, array(
                'choices'  => array(
                    'Primorsko-goranska' => 'Primorsko-goranska',
                    'Zagrebačka' => 'Zagrebačka'
                ),
            ))
            ->add('isHidden', ChoiceType::class, array(
                'choices'  => array(
                    'show location' => false,
                    'hide location' => true
                ),
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Location'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $location = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($location);
             $entityManager->flush();

             dump('redirect to home');

            return $this->redirectToRoute('home');
        }

        return $this->render('location/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

}