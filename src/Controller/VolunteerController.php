<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Repository\OrganisationRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;


#[Route('/volunteer', name: 'volunteer_')]
class VolunteerController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('volunteer/home.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/organisations', name: 'organisations_search')]
    public function searchOrganisations(OrganisationRepository $organisationRepository): Response
    {
        $organisationsMap = (new Map())
            ->fitBoundsToMarkers()
            ->zoom(2);

        $organisations = $organisationRepository->findAll();
        foreach ($organisations as $organisation) {
            $organisationName = $organisation->getName();
            $organisationPresentation = $organisation->getPresentation();
            $organisationAddress = $organisation->getAddress();
            $lat = $organisation->getAddressCoordonates()[0];
            $lon = $organisation->getAddressCoordonates()[1];
            $organisationsMap
                ->addMarker(new Marker(
                    position: new Point($lat, $lon),
                    title: $organisationName,
                    infoWindow: new InfoWindow(
                        headerContent: '<b>' . $organisationName . '</b>',
                        content: '<p>' . $organisationPresentation . '</p><i>' . $organisationAddress . '</i>'
                    )
                ));
        }

        return $this->render('volunteer/organisations-search.html.twig', [
            'organisations' => $organisations,
            'organisations_map' => $organisationsMap
        ]);
    }

    #[Route('/chat/{organisation_id}', name: 'chat')]
    public function chat(
        #[MapEntity(mapping: ['organisation_id' => 'id'])]
        Organisation $organisation,): Response
    {
        return $this->render('organisation/chat.html.twig', [
            'volunteer'       => $this->getUser()->getVolunteer(),
            'organisation' => $organisation,
        ]);
    }

}
