<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Objet;
use App\Form\ReservationType;
use App\Repository\CategorieRepository;
use App\Repository\ReservationRepository;
use App\Repository\ObjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/reservation')]
final class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, CategorieRepository $categorieRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour voir vos réservations.');
        }

        $categories = $categorieRepository->findAll();
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
            'categories' => $categories,
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(int $id, Request $request, ObjetRepository $ObjetRepository, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour réserver.');
        }

        $objectToReserve = $ObjetRepository->find($id);
        if (!$objectToReserve) {
            throw $this->createNotFoundException('L\'objet à réserver n\'existe pas.');
        }

        $categories = $categorieRepository->findAll();
        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setObjet($objectToReserve);
        $reservation->setDate(new \DateTime());

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index',
        ['categories' => $categories, 'id' => $id]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        $user = $this->getUser();
        if ($reservation->getUser() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à voir cette réservation.');
        }

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($reservation->getUser() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à modifier cette réservation.');
        }

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($reservation->getUser() !== $user) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à supprimer cette réservation.');
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->get('token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index');
    }
}
