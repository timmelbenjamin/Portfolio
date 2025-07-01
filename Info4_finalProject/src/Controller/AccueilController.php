<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('contact.html.twig',[
            'categories' => $categories,
            'message' => "",
        ]);
    }


    #[Route('/mentionsLegales', name: 'mentionsLegales')]
    public function mentionsLegales(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('mentions-legales.html.twig',[
            'categories' => $categories,
        ]);
    }

}

