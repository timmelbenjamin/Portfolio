<?php

namespace App\Controller;

use App\Entity\Points;
use App\Entity\Posts;
use App\Entity\PostsImages;
use App\Form\PostsType;
use App\Repository\CertificationsRepository;
use App\Repository\PointsRepository;
use App\Repository\PostsImagesRepository;
use App\Repository\PostsRepository;
use App\Service\GpxService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostsController extends AbstractController
{
    #[Route('/admin/posts', name: 'app_posts_index', methods: ['GET'])]
    public function index(PostsRepository $postsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('posts/index.html.twig', [
            'posts' => $postsRepository->findAll(),
        ]);
    }

    #[Route('/posts/new', name: 'app_posts_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CertificationsRepository $certificationsRepository, GpxService $gpxService, Security $security, PostsImagesRepository $postsImagesRepository): Response
    {
        $user = $security->getUser();
        if ($user) {
            $roles = $user->getRoles();
            if (in_array('ROLE_GUIDE', $roles)) {
                $isCertified = $certificationsRepository->findOneBy(['user' => $user]);
                if ($isCertified && $isCertified->isValid() == true) {
                    $is_guide = true;
                } else {
                    $is_guide = false;
                }
            } else {
                $is_guide = false;
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post, [
            'is_guide' => $is_guide
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->has('is_certified')) {
                $isCertified = $form->get('is_certified')->getData();
                if ($isCertified) {
                    $post->setIsCertified(1);
                } else {
                    $post->setIsCertified(0);
                }
            }

            $gpxFile = $form->get('gpxFile')->getData();

            if ($gpxFile) {

                $xmlContent = file_get_contents($gpxFile->getPathname());
                $xml = simplexml_load_string($xmlContent);
                $result = $gpxService->getGpxData($xml);

                $gpxData = $result['gpx_data'];
                $asc_ele = $result['asc_ele'];
                $desc_ele = $result['desc_ele'];
                $distance = $result['distance'];

                $post->setUser($user);
                $post->setAscEle($asc_ele);
                $post->setDescEle($desc_ele);
                $entityManager->persist($post);
                $entityManager->flush();

                if ($gpxData) {
                    foreach ($gpxData as $dataPoint) {
                        $point = new Points;
                        $point->setPost($post);
                        $point->setLatitude($dataPoint['lat']);
                        $point->setLongitude($dataPoint['lon']);
                        if (isset($dataPoint['ele'])) {
                            $point->setElevation($dataPoint['ele']);
                        }
                        $point->setDistance($dataPoint['distance']);
                        $point->setPosition($dataPoint['position']);

                        $entityManager->persist($point);
                    }

                    $entityManager->flush();
                    $gpxFilename = 'image-' . str_replace(' ', '', $post->getTitle()) . '_' . $post->getId() . '.' . $gpxFile->guessExtension();
                    $post->setGpxFilename($gpxFilename);
                    $entityManager->persist($post);
                    $entityManager->flush();
                }

                $images = $form->get('images')->getData();

                if ($images) {

                    foreach ($images as $file) {
                        $image = new PostsImages();

                        $image->setFilename("tmp");

                        $image->setPost($post);
                        $entityManager->persist($image);

                        $entityManager->flush();
                        $filename = 'image-' . str_replace(' ', '', $post->getTitle()) . '_' . $image->getId() . '.' . $file->guessExtension();
                        $image->setFilename($filename);
                        $entityManager->flush();

                        $file->move('uploads', $filename);
                    }
                }
            }

            return $this->redirectToRoute('app_posts_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'isCertified' => $isCertified
        ]);
    }

    #[Route('/posts/{id}', name: 'app_posts_show', methods: ['GET'])]
    public function show(Posts $post, PostsImagesRepository $postsImagesRepository, PointsRepository $pointsRepository): Response
    {
        $images = $postsImagesRepository->findBy(['post' => $post]);
        $lastPoint = $pointsRepository->findOneBy(
            ['post' => $post],
            ['position' => 'DESC']
        );
        return $this->render('posts/show.html.twig', [
            'post' => $post,
            'images' => $images,
            'lastPoint' => $lastPoint,
        ]);
    }

    #[Route('/posts/{id}/edit', name: 'app_posts_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/posts/{id}', name: 'app_posts_delete', methods: ['POST'])]
    public function delete(Request $request, Posts $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_posts_index', [
        ], Response::HTTP_SEE_OTHER);
    }
}
