<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): RedirectResponse|Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            if ($user && !is_null($user->getUserProfile())) {
                $userCategory = $user->getUserCategory();
                $userName = $user->getUserProfile()->getName();
                isset($userName) ?
                    $routePath = $userCategory . '_' . 'home' :
                    $routePath = $userCategory. '_' . 'create';
            } else {
                $routePath = 'app_logout';
            }

            return $this->redirectToRoute($routePath);
        };

        return $this->render('home/index.html.twig', [
        ]);
    }
}
