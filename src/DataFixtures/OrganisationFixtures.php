<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Persistence\ObjectManager;
use App\Service\FixturesManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrganisationFixtures extends Fixture implements DependentFixtureInterface
{
    const BDXCOORDINATES = [
        "NE" => [44.915, -0.775],
        "SW" => [44.725, -0.315],
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
        $latitude = (random_int(self::BDXCOORDINATES["SW"][0]*1000, self::BDXCOORDINATES["NE"][0]*1000))/1000;
        $longitude = (random_int( self::BDXCOORDINATES["NE"][1]*1000, self::BDXCOORDINATES["SW"][1]*1000))/1000;

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
            $organisation->setUser($this->getReference("user_organisation_" . $i));
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
        $organisation->setUser($this->getReference("user_organisation_test"));
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
