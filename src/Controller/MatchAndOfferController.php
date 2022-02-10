<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Entity\Offer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchAndOfferController extends AbstractController
{
    /**
     * @Route("/entreprise/offres-et-match", name="match_and_offers")
     */
    public function offers(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $offers = $manager->getRepository(Offer::class)->findBy([
            'company' => $user->getId()
        ]);

        $providedOffers = $manager->getRepository(Offer::class)->findBy([
            'company' => $user->getId(),
            'provided' => true,
        ]);

        $nonProvidedOffers = $manager->getRepository(Offer::class)->findBy([
            'company' => $user->getId(),
            'provided' => false,
        ]);

        return $this->render('match_and_offers/offers.html.twig', [
            'offers' => $offers,
            'providedOffers' => $providedOffers,
            'nonProvidedOffers' => $nonProvidedOffers,
        ]);
    }

    /**
     * @Route("/entreprise/match/conclus", name="match_company_concluded")
     */
    public function company_active_matches(EntityManagerInterface $manager){
        $user = $this->getUser();
        $matches_concluded = new ArrayCollection();
        $offers = $manager->getRepository(Offer::class)->findBy([
            'company' => $user->getId()
        ]);
        foreach ($offers as $offer) {
            $matches = $offer->getMatchings();
            foreach ($matches as $match){
                if($match->getAcceptedByStudent() === true){
                    $matches_concluded->add($match);
                }
            }
        }

        return $this->render('match_show/company_concluded.html.twig', [
            'matches_concluded' => $matches,
        ]);

    }

    /**
     * @Route("/entreprise/match/{id}/{status}", name="match_company_concluded_with_student", methods={"POST"})
     */
    public function company_match_conclude(EntityManagerInterface $manager, int $id, bool $status){
        $match = $manager->getRepository(Matching::class)->find($id);
        $match->setConcludedByCompany($status);
        $manager->persist($match);
        $manager->flush();
    }
    /**
     * @Route("/entreprise/offer/{id}/match/{status}", name="match_company_concluded_other",  methods={"POST"})
     */
    public function company_match_conclude_with_other(EntityManagerInterface $manager, int $id, bool $status){
        $offer = $manager->getRepository(Offer::class)->find($id);
        foreach ($offer->getMatchings() as $match){
            if($match->getAcceptedByStudent() === true){
                $match->setConcludedByCompany($status);
                $manager->persist($match);
            }
        }
        $manager->flush();

    }
}
