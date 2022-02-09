<?php

namespace App\Controller;

use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/etudiant/profil", name="profile_student")
     * @return Response
     */
    public function profileStudent(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user, ['type' => 'student']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile_student');
        }

        return $this->render('profile/student.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/entreprise/profil", name="profile_company")
     * @return Response
     */
    public function profileCompany(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user, ['type' => 'company']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile_student');
        }

        return $this->render('profile/company.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/iscom/profil", name="profile_iscom")
     * @return Response
     */
    public function profileIscom(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user, ['type' => 'admin']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile_student');
        }

        return $this->render('profile/admin.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
