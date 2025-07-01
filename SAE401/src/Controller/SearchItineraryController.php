<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\PostsSearch;
use App\Form\SearchType;
use App\Repository\PostsRepository;
use App\Repository\CommentsRepository;
use App\Service\GoogleApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

class SearchItineraryController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(Request $request, PostsRepository $postsRepository, CommentsRepository $commentsRepository, GoogleApiService $googleApiService): Response {
        $search = new PostsSearch();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        $location = $request->query->get('location');
        $cityList = [];

        if ($location) {
            $nearPlaces = $googleApiService->getNearCity($location);
            if ($nearPlaces !== null) {
                $nearPlacesData = json_decode($nearPlaces, true);
                foreach ($nearPlacesData['places'] as $place) {
                    $cityList[] = $place['displayName']['text'];
                }
            }
        }

        $itineraires = $postsRepository->findFilteredQuery($search, $cityList);

        foreach ($itineraires as $itineraire) {
            $comments = $commentsRepository->getRatingStats($itineraire->getId());

            $ratings = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

            $totalReviews = count($comments);
            $totalRating = 0;

            foreach ($comments as $comment) {
                $rating = $comment->getRating();
                if (isset($ratings[$rating])) {
                    $ratings[$rating]++;
                }
                $totalRating += $rating;
            }

            $average = $totalReviews > 0 ? round($totalRating / $totalReviews, 1) : 0;

            $itineraire->ratingStats = [
                'ratings' => $ratings,
                'average' => $average,
                'totalReviews' => $totalReviews,
            ];
        }

        return $this->render('searchItinerary.html.twig', [
            'itineraires' => $itineraires,
            'form' => $form,
        ]);
    }

    #[Route('/submit-review', name: 'submit_review', methods: ['POST'])]
    public function submitReview(Request $request, PostsRepository $postsRepository, EntityManagerInterface $entityManager, Security $security): Response
    {

        $user = $security->getUser();

        $data = json_decode($request->getContent(), true);

        $post = $postsRepository->find($data['itineraryId']);

        $comment = new Comments();
        $comment->setTitle($data['title']);
        $comment->setRating($data['rating']);
        $comment->setDate(new \DateTime($data['visitDate']));
        $comment->setMessage($data['message']);
        $comment->setPost($post); 
        $comment->setUser($user);

        $entityManager->persist($comment);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis soumis avec succès'], Response::HTTP_OK);
    }

}
