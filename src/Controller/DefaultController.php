<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/etudiant", name="default_student", methods={"GET"})
     * @return Response
     */
    public function defaultStudent(): Response
    {
        return $this->render('base.html.twig', ['role' => 'Etudiant']);
    }

    /**
     * @Route("/entreprise", name="default_company", methods={"GET"})
     * @return Response
     */
    public function defaultCompany(): Response
    {
        return $this->render('base.html.twig', ['role' => 'Entreprise']);
    }

    /**
     * @Route("/iscom", name="default_iscom", methods={"GET"})
     * @return Response
     */
    public function defaultIscom(): Response
    {
        return $this->render('base.html.twig', ['role' => 'ISCOM']);
    }

}
