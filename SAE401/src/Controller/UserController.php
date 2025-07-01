<?php

namespace App\Controller;


use App\Entity\Posts;
use App\Entity\User;
use App\Entity\Followers;
use App\Form\UserEditType;
use App\Repository\PostsRepository;
use App\Repository\UserRepository;
Use App\Repository\FollowersRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function info_profil(Security $security, Request $request, UserRepository $userRepository, PostsRepository $postsRepository, FollowersRepository $followersRepository): Response
    {
        $userId = $request->query->get('id');

        if (!$userId) {
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }
        } else {
            $user = $userRepository->find($userId);

            if (!$user) {
                $user = $this->getUser();

                if (!$user) {
                    throw $this->createNotFoundException('Utilisateur non trouvé');
                }
            }
        }

        $posts = $postsRepository->findBy(['user' => $user]);
        $nb_abo = count($followersRepository->findBy(['followed_id' => $user]));
        $nb_folo = count($followersRepository->findBy(['follower_id' => $user]));

        $user_connecte = $security->getUser();

        $subscription = $followersRepository->findOneBy([
            'follower_id' => $user_connecte,
            'followed_id' => $user
        ]);

        $isFollowing = (bool) $subscription;

        return $this->render('profil.html.twig', [
            'profil' => $user,
            'posts' => $posts,
            'nb_abo' => $nb_abo,
            'nb_folo' => $nb_folo,
            'abonne' => $isFollowing,
            'section' => 'perso'
        ]);
    }

    #[Route('/profil/post_choice ', name: 'post_choice')]
    public function infopost_choiceprofil(Security $security, PostsRepository $postsRepository): Response
    {
        $user = $security->getUser();
        $posts = $postsRepository->findBy(['user' => $user]);

        return $this->render('posts/choice.html.twig', [
            'profil' => $user,
            'posts' => $posts
        ]);
    }

    #[Route('profil/delete/{id}', name: 'itineraire_delete', methods: ['POST'])]
    #[Route('/profil/abonnements', name: 'abonnements')]
    public function abonnements(Security $security, PostsRepository $postsRepository, FollowersRepository $followersRepository): Response
    {
        $user = $security->getUser();

        $abonnements = $followersRepository->findBy(['follower_id' => $user]);

        $posts = [];

        foreach ($abonnements as $abo) {
            $abonne = $abo->getFollowedId();
            $posts_abonne = $postsRepository->findBy(['user' => $abonne]);
            $posts = array_merge($posts, $posts_abonne);
        }


        $nb_abo = count($followersRepository->findBy(['followed_id' => $user]));
        $nb_folo = count($followersRepository->findBy(['follower_id' => $user]));

        return $this->render('profil.html.twig', [
            'profil' => $user,
            'posts' => $posts,
            'nb_abo' => $nb_abo,
            'nb_folo' => $nb_folo,
            'section' => 'abonnements'
        ]);
    }

    #[Route('/profil/favoris', name: 'favoris')]
    public function favoris(Security $security, PostsRepository $postsRepository, FollowersRepository $followersRepository): Response
    {
        $user = $security->getUser();

        $nb_abo = count($followersRepository->findBy(['followed_id' => $user]));
        $nb_folo = count($followersRepository->findBy(['follower_id' => $user]));

        return $this->render('profil.html.twig', [
            'profil' => $user,
            'nb_abo' => $nb_abo,
            'nb_folo' => $nb_folo,
            'section' => 'favoris'
        ]);
    }

    #[Route('/profil/edit', name: 'profil_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour avec succès');

            return $this->redirectToRoute('profil');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/profil/delete-account', name: 'profil_delete_account', methods: ['POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete-account'.$user->getId(), $request->request->get('_token'))) {
            $security->logout(false);

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été supprimé avec succès');

            return $this->redirectToRoute('home');
        }

        $this->addFlash('error', 'Une erreur est survenue lors de la suppression de votre compte');
        return $this->redirectToRoute('profil');
    }


    #[Route('/api/search-users', name: 'api_search_users')]
    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $query = $request->query->get('q', '');

        $users = $userRepository->createQueryBuilder('u')
            ->where('u.username LIKE :query')
            ->setParameter('query', $query . '%')
            ->setMaxResults(5) 
            ->getQuery()
            ->getResult();

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ];
        }

        return new JsonResponse($results);
    }

    #[Route('/recherche', name: 'recherche_utilisateur')]
    public function rechercheUtilisateur(Request $request, UserRepository $userRepository): Response
    {
        $username = $request->query->get('username');

        if ($username) {
            $user = $userRepository->findOneBy(['username' => $username]);

            if (!$user) {
                $users = $userRepository->createQueryBuilder('u')
                    ->where('u.username LIKE :query')
                    ->setParameter('query', $username . '%')
                    ->getQuery()
                    ->getResult();

                if (count($users) === 1) {
                    $user = $users[0];
                }
                elseif (count($users) > 1) {
                    return $this->render('recherche_resultats.html.twig', [
                        'query' => $username,
                        'users' => $users
                    ]);
                }
            }

            if ($user) {
                return $this->redirectToRoute('profil', ['id' => $user->getId()]);
            }

            $this->addFlash('error', 'Aucun utilisateur trouvé avec ce nom');
            return $this->redirectToRoute('accueil');
        }

        return $this->redirectToRoute('accueil');
    }

    #[Route('/profil/delete/{id}', name: 'itineraire_delete', methods: ['POST'])]
    public function delete(Security $security, Posts $post, EntityManagerInterface $entityManager): Response
    {
        $userId = $security->getUser();

        if ($post) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profil', ['id' => $userId]);
    }

    #[Route('/profil/sabonner/{id}', name: 'sabonner', methods: ['POST'])]
    public function sabonner(int $id, Security $security, FollowersRepository $followersRepository, EntityManagerInterface $entityManager, UserRepository $userRepository,): Response
    {
        $userId = $security->getUser();
        $userToFollow = $userRepository->find($id);
        if ($followersRepository){
                $newFollow = new Followers();
                $newFollow->setFollowerId($userId);
                $newFollow->setFollowedId($userToFollow);
                $entityManager->persist($newFollow);
                $entityManager->flush();
        }

        return $this->redirectToRoute('profil', ['id' => $userToFollow->getId()]);
    }

    #[Route('/profil/desabonner/{id}', name: 'desabonner', methods: ['POST'])]
    public function desabonner(int $id, Security $security, FollowersRepository $followersRepository, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userId = $security->getUser();
        $userToFollow = $userRepository->find($id);

        $subscription = $followersRepository->findOneBy([
            'follower_id' => $userId,
            'followed_id' => $userToFollow
        ]);

        if ($subscription) {
            // Vérification optionnelle du token CSRF
            // if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->getPayload()->getString('_token')))
            $entityManager->remove($subscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profil', ['id' => $userToFollow->getId()]);
    }
}
