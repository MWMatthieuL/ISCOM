<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class StudentsSearchController extends AbstractController
{
     /**
     * @Route("/iscom/students/search", name="students_search")
     * @return Response
     */
    public function displayListAndSearch(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $students =$em->getRepository(User::class)->findBy([
            'type' => 'student'
        ]);
        return $this->render('students_search/index.html.twig',[
            "controller" => "StudentsSearchController",
            "students" => $students,
        ]);
    }

}
