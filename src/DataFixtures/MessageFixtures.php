<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    private Faker\Generator $faker;
    public function __construct() {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=10; $i++) {
            $message = new Message();
            $message->setContent($this->faker->text);
            $message->setMatching($this->getReference("matching_test"));
            $organisation = $message->getMatching()->getOrganisation();
            $volunteer = $message->getMatching()->getVolunteer();
            $i%2 === 0 ? $message->setAuthor($organisation->getUser()): $message->setAuthor($volunteer->getUser());
            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            MatchingFixtures::class,
        );
    }
}
