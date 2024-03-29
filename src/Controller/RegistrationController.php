<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Security\AppAuthenticator;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                        UserAuthenticatorInterface $authenticator ,
                      AppAuthenticator $formAuthenticator

    ): Response
    {
       $user = new User();
       $user->setRoles(["ROLE_ADMIN"]);
       $form = $this->createForm(RegistrationFormType::class, $user);
       $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
           );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request);

        }

       return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
           ]);
    }
}
