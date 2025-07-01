<?php

namespace App\Controller;

use App\Entity\PostsSearch;
use App\Form\SearchType;
use App\Repository\PostsRepository;
use App\Service\GoogleApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, UrlGeneratorInterface $urlGenerator, PostsRepository $postsRepository): Response
    {
        $search = new PostsSearch();

        $form = $this->createForm(SearchType::class, $search, [
            'action' => $urlGenerator->generate('search'),
        ]);

        $form->handleRequest($request);

        $posts = $postsRepository->findBy([], [], 3);
        
        return $this->render('index.html.twig', [
            'form' => $form ,
            'posts' => $posts
        ]);
    }

    #[Route('/guideSuccessRegister', name: 'guideSuccessRegister')]
    public function guideSuccessRegister(): Response
    {
        return $this->render('guideSuccessRegister.html.twig', [
        ]);
    }

    #[Route('/legal', name: 'legal')]
    public function legal(): Response
    {
        return $this->render('legal.html.twig', [
        ]);
    }

}
