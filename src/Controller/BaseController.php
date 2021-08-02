<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198109840983&format=json');

        $obj = json_decode($json, true);

        $profil = $obj['response']['players']['0'];

        $games = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=97F647085699DE84DDB8E41A4A5F829A&steamid=76561197960434622&format=json&include_appinfo=1');
    
        return $this->render('base/home.html.twig', [
            'profil' => $profil,            
            'games' => $games
        ]);
    }

    public function header($routeName)
    {        
        return $this->render('base/_header.html.twig', [ 
            'route_name' => $routeName
        ]);  
    }
}
