<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Repository\CategorieRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/reservation')]
class AdminReservationController extends AbstractController
{
    #[Route('/', name: 'admin_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $repo, CategorieRepository $categorieRepository): Response
    {
        $user = $this->getUser();
        $reservations = $this->isGranted('ROLE_ADMIN')
            ? $repo->findAll()
            : $repo->findReservationsByProUser($user);

        $categories = $categorieRepository->findAll();

        return $this->render('admin/reservation/index.html.twig', [
            'reservations' => $reservations,
            'categories'   => $categories,
        ]);
    }

    #[Route('/{id}', name: 'admin_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation, CategorieRepository $categorieRepository): Response
    {
        $user = $this->getUser();
        if (
            !$this->isGranted('ROLE_ADMIN')
            && $reservation->getObjet()->getUser() !== $user
        ) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $categories = $categorieRepository->findAll();

        return $this->render('admin/reservation/show.html.twig', [
            'reservation' => $reservation,
            'categories'  => $categories,
        ]);
    }
}
