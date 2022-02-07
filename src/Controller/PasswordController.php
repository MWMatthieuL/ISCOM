<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Request;


class PasswordController extends AbstractController
{
    /**
     * @Route("/etudiant/password", name="password_student")
     * @return Response
     */
    public function default(Request $request,  EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()  &&  $form->isValid() ){
            $task = $form->getData();
            $userPass = $user->getPassword(); 
 
            $user->setPassword($passwordEncoder->encodePassword($user,$task->getPassword()));
            $manager->persist($user);
            $manager->flush(); 
            return $this->redirectToRoute("password_student");
        }

        return $this->render('password/change_pass.html.twig' , [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
