<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription/accueil", name="app_register_homepage", methods={"GET"})
     * @return Response
     */
    public function registrationHomepage(string $admin = null): Response
    {
        return $this->render('security/register_homepage.html.twig', ['admin' => false]);
    }

    /**
     * @Route("/inscription/accueil/admin", name="app_register_homepage_admin", methods={"GET"})
     * @return Response
     */
    public function registrationHomepageAdmin(string $admin = null): Response
    {
        return $this->render('security/register_homepage.html.twig', ['admin' => true]);
    }

    /**
     * @Route("/connexion", name="app_login")
     * @Route("/connexion/{type}", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, string $type = null): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if (null != $type) {
            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'type' => $type
            ]);
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/inscription/etudiant", name="app_register_student")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoginFormAuthenticator $login
     * @param GuardAuthenticatorHandler $guard
     * @return Response
     */
    public function registrationStudent(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_STUDENT'])
            ->setType(User::TYPE_STUDENT);

        $form = $this->createForm(RegisterType::class, $user, ['type' => 'student']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $manager->persist($user);
            $manager->flush();

            return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'type' => 'student'
        ]);
    }

    /**
     * @Route("/inscription/entreprise", name="app_register_company")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoginFormAuthenticator $login
     * @param GuardAuthenticatorHandler $guard
     * @return Response
     */
    public function registrationCompany(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_COMPANY'])
            ->setType(User::TYPE_COMPANY);

        $form = $this->createForm(RegisterType::class, $user, ['type' => 'company']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $manager->persist($user);
            $manager->flush();

            return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'type' => 'company'
        ]);
    }

    /**
     * @Route("/inscription/admin", name="app_register_admin")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param LoginFormAuthenticator $login
     * @param GuardAuthenticatorHandler $guard
     * @return Response
     */
    public function registrationAdmin(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $this->denyAccessUnlessGranted('ROLE_ISCOM');

        $user = new User();
        $user->setRoles(['ROLE_USER', 'ROLE_ISCOM'])
            ->setType(User::TYPE_ISCOM);

        $form = $this->createForm(RegisterType::class, $user, ['type' => 'admin']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $manager->persist($user);
            $manager->flush();

            return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'type' => 'admin'
        ]);
    }
}
