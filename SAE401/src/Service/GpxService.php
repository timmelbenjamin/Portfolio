<?php

namespace App\Service;

use App\Repository\PointsRepository;
use App\Repository\PostsRepository;

class GpxService
{

    private PointsRepository $pointsRepository;
    private PostsRepository $postsRepository;

    //source : https://stackoverflow.com/questions/14939720/create-a-base-controller-class-which-implements-the-containerawareinterface
    public function __construct(PointsRepository $pointsRepository, PostsRepository $postsRepository)
    {
        $this->pointsRepository = $pointsRepository;
        $this->postsRepository = $postsRepository;
    }

    // Nous avons utilisé la formule d'harvesine qui permet de calculer la distance entre deux points à la surface de la Terre
    // Le cours est dispo ici dans la section "Distance" : https://www.movable-type.co.uk/scripts/latlong.html
    public function getDistance($previousPoint, $currentPoint)
    {
        if ($previousPoint === []) {

            return 0;
        } else {

            $radius = 6372795.477598;

            $lat1 = $currentPoint[0]['lat'];
            $lon1 = $currentPoint[0]['lon'];

            $lat2 = $previousPoint[0]['lat'];
            $lon2 = $previousPoint[0]['lon'];

            $φ1 = ($lat1 * pi()) / 180;
            $φ2 = ($lat2 * pi()) / 180;

            $Δφ = ($lat2 - $lat1) * pi() / 180;
            $Δλ = ($lon2 - $lon1) * pi() / 180;

            $a = sin($Δφ / 2) * sin($Δφ / 2) + cos($φ1) * cos($φ2) * sin($Δλ / 2) * sin($Δλ / 2);

            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

            $d = $radius * $c;

            return $d;
        }
    }

    public function getGpxData($xml)
    {
        $gpxData = [];

        $previousPoint = [];
        $currentPoint = [];

        $position = 1;
        $distance = 0;
        $asc_ele = 0;
        $desc_ele = 0;

        foreach ($xml->trk as $segment) {
            foreach ($segment->trkseg->trkpt as $point) {
                $lat = (float) $point['lat'];
                $lon = (float) $point['lon'];
                if (isset($point->ele)) {
                    $ele = (float) $point->ele;
                }
                array_push($currentPoint, ['lat' => $lat, 'lon' => $lon, 'ele' => $ele]);
                $Δd = $this->getDistance($previousPoint, $currentPoint);
                $distance += $Δd;

                if ($ele !== null && !empty($previousPoint) && isset($previousPoint[0]['ele'])) {
                    $Δele = $ele - $previousPoint[0]['ele'];

                    if ($Δele > 0) {
                        $asc_ele += $Δele;
                    } else {
                        $desc_ele += abs($Δele);
                    }
                }

                array_push($gpxData, ['lat' => $lat, 'lon' => $lon, 'ele' => $ele, 'distance' => $distance, 'position' => $position]);
                $position += 1;
                $previousPoint = [];
                array_push($previousPoint, ['lat' => $lat, 'lon' => $lon, 'ele' => $ele]);
                $currentPoint = [];
            }
        }

        return [
            'gpx_data' => $gpxData,
            'asc_ele' => $asc_ele,
            'desc_ele' => $desc_ele,
            'distance' => $distance
        ];
    }

    public function getGpxDataFrom($post)
    {
        $gpxData = $this->pointsRepository->findBy(['post' => $post]);

        return $gpxData;
    }
}
