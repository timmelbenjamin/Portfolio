<?php

namespace App\Controller;

use App\Repository\PointsRepository;
use App\Service\GpxService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use XMLReader;

#[Route('/api')]
final class ApiController extends AbstractController
{

    #[Route('/upload_gpx', name: 'upload_gpx', methods: ['POST'])]
    public function createPostApi(Request $request, GpxService $gpxService): Response
    {
        $file = $request->files->get('gpx_file');


        if ($file) {
            $xml = simplexml_load_file($file);
            $result = $gpxService->getGpxData($xml);
            $gpxData = $result['gpx_data'];

            return new JsonResponse([
                'message' => 'GPX file processed successfully',
                'gpx_data' => $gpxData,
            ], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/getGpxData', name: 'getGpxData', methods: ['POST'])]
    public function getGpxData(Request $request, PointsRepository $pointsRepository): Response
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data['post'] ?? null;

        if (!$postId) {
            return new JsonResponse(['message' => 'Post ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        $gpxData = $pointsRepository->findBy(['post' => $postId]);

        $gpxDataArray = [];
        foreach ($gpxData as $point) {
            $gpxDataArray[] = [
                'lat' => (float) $point->getLatitude(),
                'lon' => (float) $point->getLongitude(),
                'ele' => (float) $point->getElevation(),
                'distance' => (float) $point->getDistance(),
            ];
        }

        $jsonData = json_encode($gpxDataArray);

        return new JsonResponse([
            'message' => 'GPX point found successfully',
            'gpx_data' => json_decode($jsonData), 
        ], Response::HTTP_OK);
    }
}
