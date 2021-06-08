<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     * @return mixed
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render(
            'security/login.html.twig',
            [
                'error' => $authenticationUtils->getLastAuthenticationError(),
                'last_username' => $authenticationUtils->getLastUsername()
            ]
        );
    }

    /**
     * @Route("/register", name="security_register")
     * @param  Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('info', 'Konto założone. Teraz się proszę ja Ciebie zaloguj szanowny użyszkodniku.');

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('security/register.html.twig', ['form' => $form]);
    }
}
