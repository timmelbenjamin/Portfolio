<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('/category/{id}', name: 'category_show')]
    public function category(CategorieRepository $categorieRepository, Categorie $categorie): Response
    {
        $objet = $categorie->getObjets();
        $sousCategories = $categorie->getSousCategories();
        $categories = $categorieRepository->findAll();
        return $this->render('category/show.html.twig', [
            'categorie' => $categorie,
            'categories' => $categories,
            'objets' => $objet,
            'sousCategories' => $sousCategories,
        ]);
    }


    #[Route('/subcategory/{id}', name: 'subcategory_show')]
    public function subcategory(CategorieRepository $categorieRepository, SousCategorie $sousCategorie): Response
    {
        $categories = $categorieRepository->findAll();
        $objets = $sousCategorie->getObjets();
        return $this->render('subcategory/show.html.twig', [
            'categories'      => $categories,
            'sousCategorie' => $sousCategorie,
            'objets'        => $objets
        ]);
    }
}
