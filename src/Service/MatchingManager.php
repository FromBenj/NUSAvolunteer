<?php

namespace App\Service;

use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Repository\MatchingRepository;
use App\Repository\OrganisationRepository;
use App\Repository\VolunteerRepository;

class MatchingManager
{
    private OrganisationRepository $organisationRepository;
    private VolunteerRepository $volunteerRepository;
    private MatchingRepository $matchingRepository;

    public function __construct(
        OrganisationRepository $organisationRepository, VolunteerRepository $volunteerRepository,
        MatchingRepository $matchingRepository,
    )
    {

        $this->volunteerRepository = $volunteerRepository;
        $this->matchingRepository = $matchingRepository;
    }

    public function volunteerStarClasses(Array $volunteers, Organisation $organisation,) : array
    {
        $volunteerStarClasses =[];
        if ($volunteers && $organisation) {
            foreach ($volunteers as $volunteer) {
                $matching = $this->matchingRepository->findOneBy([
                    "organisation" => $organisation,
                    "volunteer" => $volunteer
                ]);
                if ($matching) {
                    $volunteerStarClasses[$volunteer->getId()] = [
                        'empty' => 'match-star d-none',
                        'filled' => 'match-star'
                    ];
                } else {
                    $volunteerStarClasses[$volunteer->getId()] = [
                        'empty' => 'match-star',
                        'filled' => 'match-star d-none'
                    ];
                }
            }
        }

    return $volunteerStarClasses;
    }

    public function getVolunteersByDescriptionWords(?string $words): array
    {
        $volunteers = [];
        $descriptionWordsList = array_filter((explode(' ', $words)), function($word) {
            return $word !== '';
        });
        foreach ($descriptionWordsList as $word) {
            $volunteersByWord = $this->volunteerRepository->findByWordInDescription($word);
            foreach($volunteersByWord as $volunteer) {
                if (!in_array($volunteer, $volunteers, true)) {
                    $volunteers[] = $volunteer;
                }
            }
        }

        return $volunteers;
    }

    public function getVolunteersByDisponibilities(?array $disponibilities): array
    {
        $volunteers = $this->volunteerRepository->findAll();
        $volunteersByDisponibilities = [];
        foreach ($volunteers as $volunteer) {
            foreach ($disponibilities as $disponibility) {
                if (in_array($disponibility, $volunteer->getDisponibilities(), true)) {
                    $volunteersByDisponibilities[] = $volunteer;
                    break;
                }
            }
        }

        return $volunteersByDisponibilities;
    }
}
