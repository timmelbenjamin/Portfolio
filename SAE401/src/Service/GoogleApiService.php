<?php

namespace App\Service;

use App\Repository\PointsRepository;
use App\Repository\PostsRepository;

class GoogleApiService
{

    private PointsRepository $pointsRepository;
    private PostsRepository $postsRepository;

    //source : https://stackoverflow.com/questions/14939720/create-a-base-controller-class-which-implements-the-containerawareinterface
    public function __construct(PointsRepository $pointsRepository, PostsRepository $postsRepository)
    {
        $this->pointsRepository = $pointsRepository;
        $this->postsRepository = $postsRepository;
    }

    public function getLocation($location)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=" . $location . "&key=AIzaSyDsRQNVaLvck3Jhk-LqJqfkUY6cpKOA-3c",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"includedTypes\": [\"locality\"],\n  \"maxResultCount\": 10,\n  \"locationRestriction\": {\n    \"circle\": {\n      \"center\": {\n        \"latitude\": 45.9032,\n        \"longitude\": 6.9213\n      },\n      \"radius\": 10000.0\n    }\n  }\n}",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Goog-FieldMask: places.displayName"
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response) {
            $data = json_decode($response, true);
            if ($data['status'] == 'OK') {
                $latitude = $data['results'][0]['geometry']['location']['lat'];
                $longitude = $data['results'][0]['geometry']['location']['lng'];
                return ['lat' => $latitude, 'lon' => $longitude];
            } else {
                return null;
            }
        }
    }

    public function getNearCity($location)
    {
        $curl = curl_init();

        $locationData = $this->getLocation($location);

        if ($locationData !== null) {

            $lat = $locationData['lat'];
            $lon = $locationData['lon'];

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://places.googleapis.com/v1/places:searchNearby",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n  \"includedTypes\": [\"locality\"],\n  \"maxResultCount\": 10,\n  \"locationRestriction\": {\n    \"circle\": {\n      \"center\": {\n        \"latitude\": $lat,\n        \"longitude\": $lon\n      },\n      \"radius\": 5000.0\n    }\n  }\n}",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "X-Goog-Api-Key: AIzaSyDsRQNVaLvck3Jhk-LqJqfkUY6cpKOA-3c",
                    "X-Goog-FieldMask: places.displayName"
                ],
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            return ($response);
        } else {
            return null;
        }

    }
}
