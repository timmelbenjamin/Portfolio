<?php

namespace App\Controller\Admin;

use App\Entity\Objet;
use App\Form\ObjetType;
use App\Repository\ObjetRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/objet')]
class AdminObjetController extends AbstractController
{
    #[Route('/', name: 'admin_objet_index', methods: ['GET'])]
    public function index(ObjetRepository $repo, CategorieRepository $categorieRepository): Response
    {
        $user   = $this->getUser();
        $objets = $this->isGranted('ROLE_ADMIN')
            ? $repo->findAll()
            : $repo->findBy(['user' => $user]);

        $categories = $categorieRepository->findAll();

        return $this->render('admin/objet/index.html.twig', [
            'objets'     => $objets,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'admin_objet_new', methods: ['GET','POST'])]
    public function new(Request $req, EntityManagerInterface $em, CategorieRepository $categorieRepository): Response
    {
        $objet = (new Objet())->setUser($this->getUser());
        $form  = $this->createForm(ObjetType::class, $objet)->handleRequest($req);
        $objet->setImg('default.png');


        $categories = $categorieRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($objet);
            $em->flush();
            return $this->redirectToRoute('admin_objet_index');
        }

        return $this->render('admin/objet/new.html.twig', [
            'form'       => $form->createView(),
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'admin_objet_show', methods: ['GET'])]
    public function show(Objet $objet, CategorieRepository $categorieRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $objet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $categories = $categorieRepository->findAll();

        return $this->render('admin/objet/show.html.twig', [
            'objet'      => $objet,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_objet_edit', methods: ['GET','POST'])]
    public function edit(Request $req, Objet $objet, EntityManagerInterface $em, CategorieRepository $categorieRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $objet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ObjetType::class, $objet)->handleRequest($req);
        $categories = $categorieRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_objet_index');
        }

        return $this->render('admin/objet/edit.html.twig', [
            'form'       => $form->createView(),
            'objet'      => $objet,
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name: 'admin_objet_delete', methods: ['POST'])]
    public function delete(Request $req, Objet $objet, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $objet->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete'.$objet->getId(), $req->request->get('_token'))) {
            $em->remove($objet);
            $em->flush();
        }
        return $this->redirectToRoute('admin_objet_index');
    }
}
