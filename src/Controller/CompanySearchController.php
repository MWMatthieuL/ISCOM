<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class CompanySearchController extends AbstractController
{
     /**
     * @Route("/iscom/company/search", name="company_search")
     * @return Response
     */
    public function displayListAndSearch(): Response
    {

        $em = $this->getDoctrine()->getManager();

        $company =$em->getRepository(User::class)->findBy([
            'type' => 'company'
        ]);
        return $this->render('company_search/index.html.twig',[
            "company" => $company,
        ]);
    }

}
