<?php

namespace App\DataFixtures;

use App\Entity\Matching;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MatchingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $matching = new Matching();
        $matching->setVolunteer($this->getReference("volunteer_test"));
        $matching->setOrganisation($this->getReference("organisation_test"));
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
