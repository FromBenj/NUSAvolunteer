<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Volunteer;
use App\Entity\Organisation;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($form->get('userCategory')->getData() === "organisation") {
                $user->setRoles(["ROLE_ORGANISATION"]);
                $user->setOrganisation(new Organisation());
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('organisation_home');
            } elseif ($form->get('userCategory')->getData() === "volunteer") {
                $user->setRoles(["ROLE_VOLUNTEER"]);
                $user->setVolunteer(new Volunteer());
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('volunteer_home');
            } else {
                return $this->redirectToRoute('app_register');
            }
        }
        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route(path: '/login/{userCategory}', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, string $userCategory): Response
    {
        if ($userCategory === "organisation") {
            $bodyColor = "organisation-login";#0A6883"";
        } elseif ($userCategory === "volunteer") {
            $bodyColor = "volunteer-login";
        } else {
            $bodyColor = null;
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($bodyColor === null) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            "bodyColor" => $bodyColor,
            "userCategory" => $userCategory,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
