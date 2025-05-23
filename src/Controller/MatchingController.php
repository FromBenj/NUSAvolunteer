<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Form\ChatType;
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
    #[Route('/matching/management', name: 'matching_management', methods: ['POST'])]
    public function matchingManagement(Request  $request, OrganisationRepository $organisationRepository,
                                       VolunteerRepository $volunteerRepository, MatchingRepository $matchingRepository,
                                       EntityManagerInterface $entityManager ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $targetId = $volunteerRepository->find($data["targetId"]);
        $profile = $data["profile"];
        $action = $data["action"];
        $availableActions = ["match", "remove", "delete"];
        $availableProfiles = ["volunteer", "organisation"];
        if (!in_array($action, $availableActions, true) || !in_array($profile, $availableProfiles, true) || (!$targetId)) {
            return new JsonResponse(
                [
                    'message' => "The request is incorrect",
                    'data' => "Wrong data",
                ]
            );
        }
        if ($profile === "volunteer") {
            $target = $volunteerRepository->find($targetId);
            $matching = $matchingRepository->findOneBy([
                "organisation" => $this->getUser()->getOrganisation(),
                "volunteer" => $target]);
        } elseif ($profile === "organisation") {
            $target = $organisationRepository->find($targetId);
            $matching = $matchingRepository->findOneBy([
                "organisation" => $target,
                "volunteer" => $this->getUser()->getOrganisation()]);
        }

        switch ($action) {
            case "match":
                if (!$matching) {
                    $matching = new Matching();
                    if ($profile === "volunteer") {
                        $matching->setOrganisation($this->getUser()->getOrganisation())->setVolunteer($target);
                        $matching->setOrgaAccepts(true)->setVoluntAccepts(false);
                    } elseif ($profile === "organisation") {
                        $matching->setVolunteer($this->getUser()->getOrganisation())->setOrganisation($target);
                        $matching->setVoluntAccepts(true)->setOrgaAccepts(false);
                    }
                } else {
                    if ($profile === "volunteer") {
                        $matching->setOrganisation($this->getUser()->getOrganisation())->setOrgaAccepts(true);
                    } elseif ($profile === "organisation") {
                        $matching->setVolunteer($this->getUser()->getOrganisation())->setVoluntAccepts(true);
                    }
                    $entityManager->persist($matching);
                    $entityManager->flush();
                }
                break;
            case "remove":
                if ($matching) {
                    if ($profile === "volunteer") {
                        $matching->setOrgaAccepts(false);
                    } elseif ($profile === "organisation") {
                        $matching->setVoluntAccepts(false);
                    }
                }
                break;
            case "delete":
                if ($matching) {
                    $entityManager->remove($matching);
                    $entityManager->flush();
                }
        }

        return new JsonResponse(
            [
                'message' => "The request is a success",
                'action' => $data["action"],
            ]
        );
    }
}
