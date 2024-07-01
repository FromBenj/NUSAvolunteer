<?php

namespace App\Service;

use App\Entity\Organisation;
use App\Repository\MatchingRepository;

class MatchingManager
{
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
}
