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
    public function getMatchingParticipants($matching): void
    {
        $rand = random_int(0, 2);
        switch ($rand) {
            case 0:
                $matching->setVolunteer($this->getReference('volunteer_' . random_int(0, 9), Volunteer::class));
                break;
            case 1:
                $matching->setOrganisation($this->getReference('organisation_' . random_int(0, 9), Organisation::class));
                break;
            case 2:
                $matching->setVolunteer($this->getReference('volunteer_' . random_int(0, 9), Volunteer::class));
                $matching->setOrganisation($this->getReference('organisation_' . random_int(0, 9), Organisation::class));
                break;
        }
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $matching = new Matching();
            $this->getMatchingParticipants($matching);
            $manager->persist($matching);
            $this->addReference('matching_' . $i, $matching);
        }

        $matching = new Matching();
        $matching->setVolunteer($this->getReference("volunteer_11", Volunteer::class));
        $matching->setOrganisation($this->getReference("organisation_11", Organisation::class));
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
