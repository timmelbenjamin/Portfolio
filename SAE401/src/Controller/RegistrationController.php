<?php

namespace App\Controller;

use App\Entity\Certifications;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        if ($user) {
            return $this->redirectToRoute('profil');
        } else {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user, [
                'guide' => false,
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();

                if ($plainPassword === $confirmPassword) {
                    $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                    $user->setProfilePicture('azure_black.svg');
                    $entityManager->persist($user);
                    $entityManager->flush();


                    return $security->login($user, 'form_login', 'main');
                } else {

                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('app_register');
                }
            }
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/register/guide', name: 'app_register_guide')]
    public function registerGuide(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        if ($user) {
            return $this->redirectToRoute('profil');
        } else {

            $user = new User();
            $certif = new Certifications();
            $form = $this->createForm(RegistrationFormType::class, $user, [
                'guide' => true,
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();
                $certifFile = $form->get('certif')->getData();

                if ($plainPassword === $confirmPassword && $certifFile) {
                    $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                    $user->setProfilePicture('azure_black.svg');
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $certif->setUser($user);
                    $certif->setFilename("tmp");
                    $entityManager->persist($certif);
                    $entityManager->flush();
                    $filename = 'degree_user_' . $user->getId() . '_' . time() . '.' . $certifFile->guessExtension();
                    $certif->setFilename($filename);
                    $entityManager->flush();

                    $certifFile->move('degree', $filename);

                    $security->login($user, 'form_login', 'main');

                    return new RedirectResponse($this->generateUrl('guideSuccessRegister'));
                } else {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('app_register_guide');
                }
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
