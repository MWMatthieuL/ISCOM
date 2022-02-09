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
}
