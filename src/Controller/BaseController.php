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

        $jsonNews = file_get_contents('http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=440&count=3&maxlength=300&format=json');

        $objNews = json_decode($jsonNews, true);

        $news = $objNews['appnews']['newsitems'];
        
        // dd($news);
        
        $jsonGames = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=97F647085699DE84DDB8E41A4A5F829A&steamid=76561198263045372&format=json&include_appinfo=1');

        $objGames = json_decode($jsonGames, true);

        $games = $objGames["response"]["games"];
        
        $jsonProfil = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198263045372');

        $objProfil = json_decode($jsonProfil, true);

        $profil = $objProfil["response"]["players"][0];

        return $this->render('base/home.html.twig', [
            'news' => $news,
            'profil' => $profil,
        ]);
    }

    public function header($routeName)
    {      
        
        $jsonProfil = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198263045372');

        $objProfil = json_decode($jsonProfil, true);

        $profil = $objProfil["response"]["players"][0];

        return $this->render('base/_header.html.twig', [ 
            'route_name' => $routeName,
            'profil' => $profil
        ]);  
    }
}

