<?php

namespace App\DataFixtures;

use App\Entity\Matching;
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
            $matching = $this->getReference("matching_test", Matching::class);
            $organisation = $matching->getOrganisation();
            $volunteer = $matching->getVolunteer();
            $message->setMatching($this->getReference("matching_test", Matching::class));
            $matching->addMessage($message);
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
