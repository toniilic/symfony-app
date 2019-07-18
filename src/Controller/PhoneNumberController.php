<?php

namespace App\Controller;

use App\Entity\PhoneNumber;
use App\Form\PhoneNumberType;
use App\Repository\PhoneNumberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phone/number")
 */
class PhoneNumberController extends AbstractController
{
    /**
     * @Route("/", name="phone_number_index", methods={"GET"})
     */
    public function index(PhoneNumberRepository $phoneNumberRepository): Response
    {
        return $this->render('phone_number/index.html.twig', [
            'phone_numbers' => $phoneNumberRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="phone_number_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $phoneNumber = new PhoneNumber();
        $form = $this->createForm(PhoneNumberType::class, $phoneNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phoneNumber);
            $entityManager->flush();

            return $this->redirectToRoute('phone_number_index');
        }

        return $this->render('phone_number/new.html.twig', [
            'phone_number' => $phoneNumber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phone_number_show", methods={"GET"})
     */
    public function show(PhoneNumber $phoneNumber): Response
    {
        return $this->render('phone_number/show.html.twig', [
            'phone_number' => $phoneNumber,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="phone_number_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PhoneNumber $phoneNumber): Response
    {
        $form = $this->createForm(PhoneNumberType::class, $phoneNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phone_number_index', [
                'id' => $phoneNumber->getId(),
            ]);
        }

        return $this->render('phone_number/edit.html.twig', [
            'phone_number' => $phoneNumber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="phone_number_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PhoneNumber $phoneNumber): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phoneNumber->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phoneNumber);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phone_number_index');
    }
}
