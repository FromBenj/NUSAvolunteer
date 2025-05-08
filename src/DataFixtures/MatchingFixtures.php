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
    public function load(ObjectManager $manager): void
    {
        $matching = new Matching();
        $matching->setVolunteer($this->getReference("volunteer_test", Volunteer::class));
        $matching->setOrganisation($this->getReference("organisation_test", Organisation::class));
        $manager->persist($matching);
        $this->addReference('matching_test', $matching);

        // Test constraint
        $matching = new Matching();
        $matching->setVolunteer(null);
        $matching->setOrganisation(null);
        $manager->persist($matching);

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
