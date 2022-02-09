<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @return Response
     */
    public function default(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    public function cgu(): Response
    {
        return $this->render('cgu.html.twig');
    }
    /**
     * @Route("/help", name="help")
     * @return Response
     */
    public function help(): Response
    {
        return $this->render('help.html.twig');
    }

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
