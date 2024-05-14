<?php

namespace App\Controller;

use App\Entity\Organisation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/organisation', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('organisation/home.html.twig', [
            //'organisation' => $this->getUser()->getProfile(),
        ]);
    }

    #[Route('/profile/{slug}', name: 'profile')]
    public function profile(Organisation $organisation): Response
    {
        return $this->render('organisation/profile.html.twig', [
            'organisation' => $organisation,
        ]);
    }
}
