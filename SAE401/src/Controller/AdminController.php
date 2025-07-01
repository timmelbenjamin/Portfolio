<?php

namespace App\Controller;

use App\Repository\CertificationsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin-home')]
    public function admin_home(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('admin/home.html.twig', [

        ]);
    }

    #[Route('/admin/certif', name: 'admin_certif')]
    public function admin_certif(CertificationsRepository $certificationsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $certif = $certificationsRepository->findBy(['is_valid' => null]);
        
        return $this->render('admin/certif.html.twig', [
            'certif' => $certif
        ]);
    }

    #[Route('/admin/certif/valid/{id}', name: 'admin_certif_valid')]
    public function admin_certif_valid(CertificationsRepository $certificationsRepository,UserRepository $userRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $certification = $certificationsRepository->find($id);

        $certification->setIsValid(1);
        
        $entityManager->persist($certification);
        $entityManager->flush();

        return $this->redirectToRoute('admin_certif');

    }

}
