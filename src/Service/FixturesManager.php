<?php
namespace App\Service;
use App\Entity\Volunteer;
use Faker;

Class FixturesManager
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

    public function __construct() {
        $this->faker = Faker\Factory::create();
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

    public function getDisponibilities(): array
    {
        $disponibilities = [];
        $disponibilityChoices = array_flip(Volunteer::DISPONIBILITYCHOICES);
        $randomValuesNumber = random_int(2, count($disponibilityChoices));
        $randKeys = array_rand($disponibilityChoices, $randomValuesNumber);
        foreach($randKeys as $key) {
            $disponibilities[] = $disponibilityChoices[$key];
        }

        return $disponibilities;
    }
}

