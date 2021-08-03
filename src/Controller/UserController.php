<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        $jsonGames = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=97F647085699DE84DDB8E41A4A5F829A&steamid=76561198263045372&format=json&include_appinfo=1');

        $objGames = json_decode($jsonGames, true);

        $count = $objGames['response'];

        $games = $objGames["response"]["games"];

        $jsonProfil = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198263045372
        ');

        $objProfil = json_decode($jsonProfil, true);

        $profil = $objProfil["response"]["players"][0];

        $jsonFriends = file_get_contents('http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=97F647085699DE84DDB8E41A4A5F829A&steamid=76561198263045372&relationship=friend');

        $objFriends = json_decode($jsonFriends, true);

        $friends = $objFriends['friendslist']["friends"]; 
       
        foreach ($friends as $friend) {

            $jsonFriendInfo = file_get_contents(sprintf('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=%s', $friend['steamid']));
            
            $objFriendInfo = json_decode($jsonFriendInfo, true);
            
            $profilFriend = $objFriendInfo["response"]["players"][0];

            $friendsList[] = $profilFriend;
        }        

        return $this->render('user/index.html.twig', [
            'profil'  => $profil,            
            'games'   => $games,
            'count'   => $count,
            'friends' => $friends,
            'friendsList' => $friendsList            
            
        ]);
    }
}
