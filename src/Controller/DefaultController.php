<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}
