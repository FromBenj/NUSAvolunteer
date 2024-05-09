<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/organisation', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profileView(): Response
    {
        return $this->render('organisation/profile.html.twig', [
            'organisation' => $this->getUser()->getuserID(),
        ]);
    }
}
