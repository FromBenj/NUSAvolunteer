<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Volunteer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use FixturesManager;

class VolunteerFixtures extends Fixture
{
    private Faker\Generator $faker;
    private FixturesManager $fixturesManager;

    public function __construct() {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->fixturesManager = new FixturesManager();
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<10; $i++) {
            $volunteer = new Volunteer();
            $volunteer->setFirstName($this->faker->firstName);
            $volunteer->setLastName($this->faker->lastName);
            $volunteer->setAddress($this->faker->address);
            $volunteer->setVolunteerPictureName("https://i.pravatar.cc/150?img=" . rand(1, 70));
            $volunteer->setDescription($this->faker->text);
            $volunteer->setDisponibilities($this->fixturesManager->getDisponibilities());
            $volunteer->setKeywords($this->fixturesManager->getKeywords());
            $manager->persist($volunteer);
        }

        $volunteer = new Volunteer();
        $volunteer->setFirstName($this->faker->firstName);
        $volunteer->setLastName($this->faker->lastName);
        $volunteer->setAddress($this->faker->address);
        $volunteer->setVolunteerPictureName("https://i.pravatar.cc/150?img=" . rand(1, 70));
        $volunteer->setDescription($this->faker->text);
        $volunteer->setDisponibilities($this->fixturesManager->getDisponibilities());
        $volunteer->setKeywords($this->fixturesManager->getKeywords());
        $manager->persist($volunteer);
        $this->addReference('volunteer_test', $volunteer);

        $manager->flush();
    }
}
