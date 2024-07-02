<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Faker\Generator $faker;
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface) {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->faker = Faker\Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {
        // Organisation user;
        for ($i=0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword(
                    $user, 'organisation' . $i)
            );
            $user->setRoles(["ROLE_ORGANISATION"]);
            $manager->persist($user);
            $this->addReference("user_organisation_" . $i, $user);
        }

        $user = new User();
        $user->setEmail("o@o.com");
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "o")
            );
        $user->setRoles(["ROLE_ORGANISATION"]);
        $manager->persist($user);
        $this->addReference("user_organisation_test", $user);


        // Volunteer user;
        for ($i=0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword(
                    $user, 'volunteer' . $i)
            );
            $user->setRoles(["ROLE_VOLUNTEER"]);
            $manager->persist($user);
            $this->addReference("user_volunteer_" . $i, $user);
        }

        $user = new User();
        $user->setEmail("v@v.com");
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "v")
            );
        $user->setRoles(["ROLE_VOLUNTEER"]);
        $manager->persist($user);
        $this->addReference("user_volunteer_test", $user);

        $manager->flush();
    }
}
