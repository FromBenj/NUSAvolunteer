<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Form\OrganisationType;
use App\Form\SearchVolunteersType;
use App\Repository\MatchingRepository;
use App\Repository\VolunteerRepository;
use App\Service\MatchingManager;
use App\Service\PictureManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/organisation', name: 'organisation_')]
class OrganisationController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(PictureManager $pictureManager): Response
    {
        $organisation = $this->getUser()->getOrganisation();
        $pictureManager->checkOrganisationPictures($organisation);
        if (!$organisation->getName()) {
            return $this->redirectToRoute('organisation_edit');
        }

        return $this->render('organisation/home.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, PictureManager $pictureManager): Response
    {
        $organisation = $this->getUser()->getOrganisation();
        $editForm = $this->createForm(OrganisationType::class, $organisation);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $data = $editForm->getData();
            if ($data->getAvatarFile()) {
                $avatarName = $pictureManager->getUniqueName($data->getAvatarFile()->getClientOriginalName());
                $file = $data->getAvatarFile();
                $parameters = $pictureManager->getParameters();
                $file->move($parameters->get("upload_directory_organisation_images") ."/avatars", $avatarName);
                $data->setAvatarName($avatarName);
                $data->removeAvatarFile();
            }

            $entityManager->flush();


            return $this->redirectToRoute('organisation_home');
        }

        return $this->render('organisation/edit.html.twig', [
            'organisation' => $organisation,
            'editForm' => $editForm->createView(),
        ]);
    }

    #[Route('/volunteers', name: 'volunteers_search')]
    public function searchVolunteers(VolunteerRepository $volunteerRepository, MatchingRepository $matchingRepository,
                                     MatchingManager $matchingManager, Request $request): Response
    {
        $volunteers = $volunteerRepository->findAll();
        $volunteerStarClasses = $matchingManager->volunteerStarClasses($volunteers, $this->getUser()->getOrganisation(), $matchingRepository);

        $form = $this->createForm(SearchVolunteersType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !empty(array_filter($form->getData()))) {
            $volunteers = [];
            $volunteersSearch = $form->getData();
            $nbreCriteria = count(array_filter($volunteersSearch));
            $volunteersFromName = $volunteerRepository->findByVolunteerName($volunteersSearch['name']);
            $volunteersFromDescription = $matchingManager->getVolunteersByDescriptionWords($volunteersSearch['description']);
            $volunteersFromDisponibilities = $matchingManager->getVolunteersByDisponibilities($volunteersSearch['disponibilities']);
            $mergedVolunteers = array_merge($volunteersFromName, $volunteersFromDescription, $volunteersFromDisponibilities);
            if ($nbreCriteria === 1) {
                foreach ($mergedVolunteers as $volunteer) {
                    if (!in_array($volunteer, $volunteers, true)) {
                        $volunteers[] = $volunteer;
                    }
                }
            } else {
                $volunteersId = [];
                foreach($mergedVolunteers as $volunteer) {
                    $volunteersId[] = $volunteer->getId();
                    if (!in_array($volunteer, $volunteers, true) && array_count_values($volunteersId)[$volunteer->getId()] > 1) {
                        $volunteers[] = $volunteer;
                    }
                }
            }

            foreach($volunteers as $volunteer) {
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

        return $this->render('organisation/volunteers-search.html.twig', [
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

    #[Route('/chat/{volunteer_id}', name: 'chat')]
    public function chat(
        #[MapEntity(mapping: ['volunteer_id' => 'id'])]
        Volunteer $volunteer): Response
    {
        return $this->render('organisation/chat.html.twig', [
            'organisation'          => $this->getUser()->getOrganisation(),
            'volunteer' => $volunteer,
        ]);
    }
}
