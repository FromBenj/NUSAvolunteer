<?php

namespace App\Controller;

use App\Form\SearchVolunteersType;
use App\Repository\MatchingRepository;
use App\Repository\VolunteerRepository;
use App\Service\MatchingManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/organisation', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('organisation/home.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(): Response
    {
        $organisation = $this->getUser()->getOrganisation();

        return $this->render('organisation/edit.html.twig', [
            'organisation' => $organisation,
        ]);
    }

    #[Route('/search', name: 'search')]
    public function searchVolunteers(VolunteerRepository $volunteerRepository, MatchingRepository $matchingRepository,
                                     MatchingManager $matchingManager, Request $request): Response
    {
        $volunteers = $volunteerRepository->findAll();
        $volunteerStarClasses = $matchingManager->volunteerStarClasses($volunteers, $this->getUser()->getOrganisation(), $matchingRepository);

        $form = $this->createForm(SearchVolunteersType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $volunteers = [];
            $volunteersSearch = $form->getData();
            $volunteersFromName = $volunteerRepository->findByVolunteerName($volunteersSearch['name']);
            $volunteersFromDescription = $matchingManager->getVolunteersByDescriptionWords($volunteersSearch['description']);
            $volunteersFromDisponibilities = $matchingManager->getVolunteersByDisponibilities($volunteersSearch['disponibilities']);
            $mergedVolunteers = array_merge($volunteersFromName, $volunteersFromDescription, $volunteersFromDisponibilities);
            foreach($mergedVolunteers as $volunteer) {
                if(!in_array($volunteer, $volunteers, true)) {
                    $volunteers[] = $volunteer;
                    $volunteerStarClasses[$volunteer->getId()] = [
                        'empty' => 'match-star',
                        'filled' => 'match-star d-none'
                    ];
                    $matching = $matchingRepository->findOneBy([
                        'volunteer'=> $volunteer,
                        'organisation'=> $this->getUser()->getOrganisation()
                    ]);
                    if ($matching) {
                        $volunteerStarClasses[$volunteer->getId()] = [
                            'empty' => 'match-star d-none',
                            'filled' => 'match-star'
                        ];
                    }
                }
            }
        }

        return $this->render('organisation/search.html.twig', [
            'volunteers' => $volunteers,
            'form' => $form->createView(),
            'starClasses' => $volunteerStarClasses,
        ]);
    }

    #[Route('/matches', name: 'matches')]
    public function matching(MatchingRepository $matchingRepository): Response
    {
        $matches = $matchingRepository->findByOrganisationAndOrdered($this->getUser()->getOrganisation());

        return $this->render('organisation/matches.html.twig', [
            'matches' => $matches,
        ]);
    }
}
