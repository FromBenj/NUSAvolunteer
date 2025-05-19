<?php

namespace App\Service;

use App\Entity\Organisation;
use App\Entity\Volunteer;
use App\Repository\MatchingRepository;
use App\Repository\VolunteerRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatchingManager
{
    private VolunteerRepository $volunteerRepository;

    public function __construct(VolunteerRepository $volunteerRepository)
    {
        $this->volunteerRepository = $volunteerRepository;
    }

    public function matchingEntityValidation(
        MatchingRepository $matchingRepository, Organisation $organisation, Volunteer $volunteer): void
{
    $existingMatching = $matchingRepository->findOneBy([
        'organisation' => $organisation,
        'volunteer' => $volunteer,
    ]);

    if ($existingMatching !== null || ($organisation === null && $volunteer === null)) {
        throw new \InvalidArgumentException('The matching already exists or both organisation and volunteer are not set.');
    }
}


    public function volunteerStarClasses(Array $volunteers, Organisation $organisation,
                                         MatchingRepository $matchingRepository) : array
    {
        $volunteerStarClasses =[];
        if ($volunteers && $organisation) {
            foreach ($volunteers as $volunteer) {
                $matching = $matchingRepository->findOneBy([
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
