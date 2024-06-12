<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $userPasswordHasherInterface;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface) {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }


    public function load(ObjectManager $manager): void
    {
        // Organisation user;
        $user = new User();
        $user->setEmail("o@o.com");
        $user->setPassword(
            $this->userPasswordHasherInterface->hashPassword(
                $user, "o")
            );
        $user->setRoles(["ROLE_ORGANISATION"]);
        $user->setOrganisation($this->getReference("organisation_test"));
        $manager->persist($user);

        // Volunteer user;

        

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            OrganisationFixtures::class,
            );
    }
}
