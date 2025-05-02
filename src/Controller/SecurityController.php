<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $bodyColor = "register-bg";
        $buttonColor = "register-button";
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $userCategory = $form->get('userCategory')->getData();
            if ( $userCategory === "organisation" || $userCategory === "volunteer") {
                $entityManager->persist($user);
                $entityManager->flush();
                $providerKey = 'secured_area';
                $token = new UsernamePasswordToken($user, $providerKey, $user->getRoles());
                $this->container->get('security.token_storage')->setToken($token);
            }

            if ($user->getVolunteer() && !$user->getOrganisation()) {
            return $this->redirectToRoute('volunteer_home');
            }

            if ($user->getOrganisation() && !$user->getVolunteer()) {
                return $this->redirectToRoute('organisation_home');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
            "bodyColor" => $bodyColor,
            "buttonColor" => $buttonColor,
        ]);
    }

    #[Route(path: '/login/{userCategory}', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, ?string $userCategory = null): Response
    {
        if ($userCategory === "organisation") {
            $bodyColor = "main-organisation-bg";
            $buttonColor = "main-volunteer-button";
        } elseif ($userCategory === "volunteer") {
            $bodyColor = "main-volunteer-bg";
            $buttonColor = "main-organisation-button";
        } else {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            "bodyColor" => $bodyColor,
            "buttonColor" => $buttonColor,
            "userCategory" => $userCategory,
        ]);
    }

    #[Route(path: '/login/distribution', name: 'app_login_distribution')]
    public function loginDistribution(): RedirectResponse
    {
        $user = $this->getUser();
        if ($user && !is_null($user->getUserProfile())) {
            $userCategory = $user->getUserCategory();
            $userName = $user->getUserProfile()->getName();
            isset($userName) ?
            $routePath = $userCategory . '_' . 'home' :
            $routePath = $userCategory. '_' . 'create';
        } else {
            $routePath = 'app_home';
        }

        return $this->redirectToRoute($routePath);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
