<?php

namespace App\DataFixtures;

use App\Entity\Matching;
use App\Entity\Organisation;
use App\Entity\Volunteer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MatchingFixtures extends Fixture implements DependentFixtureInterface
{
    public function getRandomProfilesOrNull(): array
    {
        $randVolunteer = random_int(0,15);
        $randOrganisation =  random_int(0,15);
        $randVolunteer = $randVolunteer > 10 ? null : 'volunteer_' . $randVolunteer;
        $randOrganisation = $randOrganisation> 10 ? null : 'organisation_' . $randOrganisation ;
        if ($randVolunteer === null && $randOrganisation === null){
            $randOrganisation =  'organisation_' . random_int(0,10);
        }

        return [$randVolunteer, $randOrganisation];
    }
    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i < 20; $i++) {
            $rand = $this->getRandomProfilesOrNull();
            $matching = new Matching();
            $matching->setVolunteer($this->getReference($rand[0], Volunteer::class));
            $matching->setOrganisation($this->getReference($rand[1], Organisation::class));
            $manager->persist($matching);
            $this->addReference('matching_test', $matching);
        }

        $matching = new Matching();
        $matching->setVolunteer($this->getReference("volunteer_test", Volunteer::class));
        $matching->setOrganisation($this->getReference("organisation_test", Organisation::class));
        $manager->persist($matching);
        $this->addReference('matching_test', $matching);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            OrganisationFixtures::class,
            VolunteerFixtures::class,
        );
    }
}
