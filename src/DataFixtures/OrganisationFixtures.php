<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Persistence\ObjectManager;
use App\Service\FixturesManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;

class OrganisationFixtures extends Fixture implements DependentFixtureInterface
{
    const BDXCOORDINATES = [
        "NE" => [45.312, -0.058],
        "SW" => [44.572, -1.115],
    ];

    private Faker\Generator $faker;
    private SluggerInterface $slugger;
    private FixturesManager $fixturesManager;

    public function __construct(SluggerInterface $slugger) {
        $this->faker = Faker\Factory::create('fr_FR');
        $this->slugger = $slugger;
        $this->fixturesManager = new FixturesManager();
    }

    public function randomCoordinates(): array {
        $latSW = self::BDXCOORDINATES["SW"][0];
        $latNE = self::BDXCOORDINATES["NE"][0];
        $lonSW = self::BDXCOORDINATES["SW"][1];
        $lonNE = self::BDXCOORDINATES["NE"][1];
        $latitude = random_int(min($latSW, $latNE)*1000, max($latSW, $latNE)*1000)/1000;
        $longitude = random_int( min($lonSW, $lonNE)*1000, max($lonSW, $lonNE)*1000)/1000;

        return [$latitude, $longitude];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<10; $i++) {
            $organisation = new Organisation();
            $organisation->setName($this->faker->company);
            $organisation->setAddress($this->faker->address);
            $organisation->setAddressCoordonates($this->randomCoordinates());
            $organisation->setRepresentative($this->fixturesManager->getFullName());
            $organisation->setSlug($this->slugger->slug($organisation->getName()));
            $organisation->setPresentation($this->faker->text);
            $organisation->setKeywords($this->fixturesManager->getKeywords());
            for ($j=0; $j<4; $j++) {
                $organisation->addLink($this->faker->url);
            }
            $organisation->setUser($this->getReference("user_organisation_" . $i, User::class));
            $manager->persist($organisation);
        }

        $organisation = new Organisation();
        $organisation->setName("ASTI");
        $organisation->setAddress($this->faker->address);
        $organisation->setAddressCoordonates($this->randomCoordinates());
        $organisation->setRepresentative("Beatriz Liu");
        $organisation->setSlug($this->slugger->slug($organisation->getName()));
        $organisation->setPresentation($this->faker->text);
        $organisation->setKeywords($this->fixturesManager->getKeywords());
        for ($j=0; $j<4; $j++) {
            $organisation->addLink($this->faker->url);
        }
        $organisation->setUser($this->getReference("user_organisation_test", User::class));
        $manager->persist($organisation);
        $this->addReference('organisation_test', $organisation);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
