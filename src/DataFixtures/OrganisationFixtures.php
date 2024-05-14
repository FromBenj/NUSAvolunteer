<?php

namespace App\DataFixtures;

use App\Entity\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class OrganisationFixtures extends Fixture
{
    private const KEYWORDS = [
        "#international",
        "#nutrition",
        "#poverty",
        "#saveWater",
        "#wasteManagement",
        "#medical"
        ];

    private Faker\Generator $faker;
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->faker = Faker\Factory::create();
        $this->slugger = $slugger;
    }

    public function getFullName(): string
    {
        $firstname = $this->faker->firstName;
        $lastname = $this->faker->lastName;

        return $firstname . ' ' . $lastname;

    }

    public function getKeywords(): array
    {
        $keywords = [];
        $keywordsNumber = random_int(0, count(self::KEYWORDS));
        if ($keywordsNumber === 1) {
            $keywords[] = self::KEYWORDS[$keywordsNumber];
        } elseif ($keywordsNumber > 1) {
            $keywordsKeys = array_rand(self::KEYWORDS, $keywordsNumber);
            foreach ($keywordsKeys as $key) {
                $keywords[] = self::KEYWORDS[$key];
            }
        }

        return $keywords;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<10; $i++) {
            $organisation = new Organisation();
            $organisation->setName($this->faker->company);
            $organisation->setAddress($this->faker->address);
            $organisation->setRepresentative($this->getFullName());
            $organisation->setSlug($this->slugger->slug($organisation->getName()));
            $organisation->setDescription($this->faker->text);
            $organisation->setKeywords($this->getKeywords());
            for ($j=0; $j<4; $j++) {
                $organisation->addLink($this->faker->url);
            }
            $manager->persist($organisation);
        }

        $manager->flush();
    }
}
