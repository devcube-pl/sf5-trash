<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function register(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // @todo dodac obsluge formularza

        return $this->renderForm('security/register.html.twig', ['form' => $form]);
    }
}
