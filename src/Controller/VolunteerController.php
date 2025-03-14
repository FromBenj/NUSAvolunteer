<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Repository\OrganisationRepository;
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
            ->zoom(6);

        $organisationsMap
            ->addMarker(new Marker(
                position: new Point(45.7640, 4.8357),
                title: 'Lyon',
                infoWindow: new InfoWindow(
                    headerContent: '<b>Lyon</b>',
                    content: 'The French town in the historic Rhône-Alpes region, located at the junction of the Rhône and Saône rivers.'
                )
            ));

        $organisations = $organisationRepository->findAll();
        return $this->render('volunteer/organisations-search.html.twig', [
            'organisations' => $organisations,
            'organisations_map' => $organisationsMap
        ]);
    }
}
