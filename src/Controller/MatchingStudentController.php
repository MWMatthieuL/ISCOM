<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Matching;
use App\Repository\MatchingRepository;

class MatchingStudentController extends AbstractController
{

    /**
    * @Route("/etudiant/match", name="match_student")
    */
    public function student_match(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $matches = $manager->getRepository(Matching::class)->findBy([
            'student' => $user->getId(),
            'sendByCompany' => true
        ]);
        $matches_accepted = new ArrayCollection();
        $matches_rejected = new ArrayCollection();

        foreach ($matches as $match) {
            if($match->getAcceptedByStudent() === true){
            $matches_accepted->add($match);
            }else if($match->getAcceptedByStudent() === false){
            $matches_rejected->add($match);
            }
        }

        return $this->render('match_show/student.html.twig', [
        'matches' => $matches,
        'matches_accepted' => $matches_accepted,
        'matches_rejected' => $matches_rejected,
        ]);
    }

    /**
     * @Route("/etudiant/match/{id}/{status}", name="match_student_set_status", methods={"POST"})
     *
     * @param EntityManagerInterface $manager
     * @param int                    $id
     * @param string                 $status
     *
     * @return mixed
     */
    public function set_status_match(EntityManagerInterface $manager, int $id, string $status)
    {
        $match = $manager->getRepository(Matching::class)->find($id);
        if($status === "rejected"){
            $match->setAcceptedByStudent(false);
        }elseif($status === "accepted"){
            $match->setAcceptedByStudent(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($match);
        $em->flush();

        return $this->redirectToRoute('match_student');
    }

    /**
     * @Route("/etudiant/match/{id}/offer",  name="match_student_view_offer_file", methods={"GET"})
     *
     * @param EntityManagerInterface $manager
     * @param int                    $id
     *
     * @return mixed
     */
    public function display_offer(EntityManagerInterface $manager, int $id){
        $match = $manager->getRepository(Matching::class)->find($id);
        $offer = $match->getOffer();
        return $this->render('offer_file.html.twig', ["file" => $offer->getOfferFile()]);
    }

    /**
     * @Route("/etudiant/match/conclus", name="match_student_concluded")
     */
    public function student_concluded_matches(EntityManagerInterface $manager){

        $user = $this->getUser();
        $matches = $manager->getRepository(Matching::class)->findBy([
            'student' => $user->getId(),
            'sendByCompany' => true,
            'acceptedByStudent' => true
        ]);
        return $this->render('match_show/student_concluded.html.twig', [
            'matches_concluded' => $matches,
        ]);

    }
}
