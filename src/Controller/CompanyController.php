<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{

    /**
     * @Route("/iscom/company", name="company_iscom")
     * @param Request $request
     * @return Response
     */
    public function companyIscom(Request $request): Response
    {

    }

}
