<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/api/champions", name="api_champion")
     * @param HttpClientInterface $httpClient
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(HttpClientInterface $httpClient)
    {

        $response = $httpClient->request("GET", 'http://ddragon.leagueoflegends.com/cdn/10.11.1/data/fr_FR/champion.json');

        if ($response->getStatusCode() === Response::HTTP_NOT_FOUND){
            throw new NotFoundHttpException();
        }
        $champions = $response->toArray();
        $champions = $champions['data'];

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'champions' => $champions
        ]);
    }


    /**
     * @Route("/api/champion/{id}", name="api_champion_show")
     */
    public function show(string $id, HttpClientInterface $httpClient){

        $response = $httpClient->request("GET", "http://ddragon.leagueoflegends.com/cdn/10.11.1/data/fr_FR/champion/$id.json");
        $champion = $response->toArray();
        $champion = $champion['data'][$id];



        return $this->render('home/show.html.twig', [
            'controller_name' => 'HomeController',
            'champion' => $champion
        ]);
    }
}
