<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Repository\OrganisationRepository;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat', name: 'chat-')]
class ChatController extends AbstractController
{
    #[Route('/chat/{organisationId}/{volunteerId}', name: 'chat')]
    public function chat(Organisation $organisationId ,Volunteer $volunteerId,
                         OrganisationRepository $OrganisationRepository, VolunteerRepository $volunteerRepository ): Response
    {
        $organisation = $OrganisationRepository->find($organisationId);
        $volunteer = $volunteerRepository->find($volunteerId);

        return $this->render('organisation/chat.html.twig', [
            'volunteer' => $volunteer,
            'organisation' => $organisation,
        ]);
    }
}
