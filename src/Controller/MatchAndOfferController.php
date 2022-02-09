<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MatchAndOfferController extends AbstractController
{
    /**
     * @Route("/entreprise/offres-et-match", name="match_and_offers")
     */
    public function offers(): Response
    {
        $user = $this->getUser();

        $offers = $user->getOffers();

        $nonProvidedOffers = new ArrayCollection();
        $providedOffers = new ArrayCollection();

        foreach ($offers as $offer) {
            if($offer->isProvided()) {
                $providedOffers->add($offer);
            } else {
                $nonProvidedOffers->add($offer);
            }
        }

        return $this->render('match_and_offers/offers.html.twig', [
            'offers' => $offers,
            'providedOffers' => $providedOffers,
            'nonProvidedOffers' => $nonProvidedOffers,
        ]);
    }
}
