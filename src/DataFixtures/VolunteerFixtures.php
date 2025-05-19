<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\Volunteer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Service\FixturesManager;

class VolunteerFixtures extends Fixture implements DependentFixtureInterface
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
            $volunteer->setname($this->faker->firstName);
            $volunteer->setLastName($this->faker->lastName);
            $volunteer->changeSlug();
            $volunteer->setAddress($this->faker->address);
            $volunteer->setpictureName("https://i.pravatar.cc/150?img=" . rand(1, 70));
            $volunteer->setDescription($this->faker->text);
            $volunteer->setDisponibilities($this->fixturesManager->getDisponibilities());
            $volunteer->setKeywords($this->fixturesManager->getKeywords());
            $volunteer->setUser($this->getReference("user_volunteer_" . $i, User::class));
            $manager->persist($volunteer);
            $this->addReference('volunteer_'  . $i, $volunteer);
        }

        $volunteer = new Volunteer();
        $volunteer->setname($this->faker->firstName);
        $volunteer->setLastName($this->faker->lastName);
        $volunteer->changeSlug();
        $volunteer->setAddress($this->faker->address);
        $volunteer->setpictureName("https://i.pravatar.cc/150?img=" . rand(1, 70));
        $volunteer->setDescription($this->faker->text);
        $volunteer->setDisponibilities($this->fixturesManager->getDisponibilities());
        $volunteer->setKeywords($this->fixturesManager->getKeywords());
        $volunteer->setUser($this->getReference("user_volunteer_test", User::class));
        $manager->persist($volunteer);
        $this->addReference("volunteer_11", $volunteer);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
