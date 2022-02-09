<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class StudentShowController extends AbstractController
{
    /**
     * @Route("iscom/student/{studentId}", name="student_show")
     * @return Response
     */
    public function index(User $studentId): Response
    {
        $em = $this->getDoctrine()->getManager();

        $student = $em->getRepository(User::class)->findBy([
            'id' => $studentId
        ]);/* 
        dd($student); */
        return $this->render('student_show/index.html.twig', [
            'student' => $student
        ]);
    }
}
