<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Form\SearchVolunteersType;
use App\Repository\OrganisationRepository;
use App\Repository\VolunteerRepository;
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
    public function searchVolunteers(VolunteerRepository $volunteerRepository, Request $request): Response
    {
        $volunteers = $volunteerRepository->findAll();

        $form = $this->createForm(SearchVolunteersType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $volunteers = [];
            $allVolunteersSearchedId = [];
            $volunteersSearch = $form->getData();
            $volunteersFromName = $volunteerRepository->findByVolunteerName($volunteersSearch);
            $volunteersFromDescription = $volunteerRepository->findByWordsInText($volunteersSearch);
            $volunteersFromDisponibilities = $volunteerRepository->findByDisponibilities($volunteersSearch);
            $allVolunteersSearched = array_merge($volunteersFromName, $volunteersFromDescription, $volunteersFromDisponibilities);
            foreach($allVolunteersSearched as $volunteer) {
                $allVolunteersSearchedId[] = $volunteer->getId();
            }
            foreach($allVolunteersSearchedId as $volunteerId) {
                $volunteer = $volunteerRepository->find($volunteerId);
                if (array_count_values($allVolunteersSearchedId)[$volunteerId] > 1 && !in_array($volunteer, $volunteers, true)) {
                    $volunteers[] = $volunteer;
                }
            }
            if(count($volunteers) === 0) {
                $volunteers = $allVolunteersSearched;
            }
        }

        return $this->render('organisation/search.html.twig', [
            'volunteers' => $volunteers,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/chat/{slug}', name: 'chat')]
    public function chat(Volunteer $volunteer): Response
    {
        $user = $this->getUser();

        return $this->render('organisation/chat.html.twig', [
            'volunteer' => $volunteer,
            'organisation' => $user->getOrganisation(),
        ]);
    }
}
