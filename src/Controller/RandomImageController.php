<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RandomImageController extends AbstractController
{
    #[Route('/random/image', name: 'app_random_image')]
    public function index(): Response
    {
        $images = [];
        //Try to get images from API or send flash message
        try {

            //Create objet HttpClient
            $httpClient = HttpClient::create();
            //Make a request to API of picsum
            $response = $httpClient->request('GET', 'https://picsum.photos/v2/list?limit=9');

            // Convert Json response to array
            $data = $response->toArray();

            //Extract img url from data
            $images = array_map(function ($image) {
                return $image['download_url'];
            }, $data);
        } catch (\Exception $e) {
            $this->addFlash('error', 'les images n\'ont pas pu être chargées');
        }


        return $this->render('random_image/index.html.twig', [
            'images' => $images,
        ]);
    }
}
