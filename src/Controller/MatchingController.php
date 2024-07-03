<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Repository\MatchingRepository;
use App\Repository\OrganisationRepository;
use App\Repository\VolunteerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MatchingController extends AbstractController
{
    #[Route('/matching/management', name: 'matching_management',methods: ['POST'])]
    public function matchingManagement(Request $request, OrganisationRepository $organisationRepository,
    VolunteerRepository $volunteerRepository, MatchingRepository $matchingRepository, EntityManagerInterface $entityManager
    ) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $organisation = $organisationRepository->find($data["organisationId"]);
        $volunteer = $volunteerRepository->find($data["volunteerId"]);
        $dataActions = ["create", "delete"];
        if (!in_array($data["action"], $dataActions) || !$organisation || !$volunteer) {
            return new JsonResponse(
                [
                'message' => "The request is incorrect",
                'data' => "Impossible to get the data",
                ]
            );
        }
        $matching = $matchingRepository->findOneBy([
            "organisation" => $organisation,
            "volunteer" => $volunteer]);
        if ($data["action"] === "create" && !$matching) {
            $matching = new Matching();
            $matching->setOrganisation($organisation)->setVolunteer($volunteer);
            $entityManager->persist($matching);
            $entityManager->flush();
        } elseif ($data["action"] === "delete" && $matching) {
                $entityManager->remove($matching);
                $entityManager->flush();
        } else {
            return new JsonResponse(
                [
                'message' => "The request is incorrect",
                'data' => $data,
                ]
            );
        }

        return new JsonResponse(
            [
            'message' => "The request is a success",
            'action' => $data["action"],
            ]
        );
    }

    #[Route('chat/{organisation_id}/{volunteer_id}', name: 'chat')]
    public function chat(
        #[MapEntity(mapping: ['organisation_id' => 'id'])]
        Organisation $organisation,
        #[MapEntity(mapping: ['volunteer_id' => 'id'])]
        Volunteer $volunteer,
        MatchingRepository $matchingRepository): Response
    {
        $matching = $matchingRepository->findOneBy( [
            "organisation" => $organisation,
            "volunteer" => $volunteer
        ]);

        if (!$matching) {
            return $this->redirectToRoute('app_home');
        }

        $userProfile = $this->getUser()->getUserProfile();


        return $this->render('matching/chat.html.twig', [
            'matching' => $matching,
            'userMatchings' => $userProfile->getMatchings(),
        ]);
    }
}
