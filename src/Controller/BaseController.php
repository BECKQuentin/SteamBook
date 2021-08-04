<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Tchat;
use App\Form\ChannelType;
use App\Form\TchatType;
use App\Repository\ChannelRepository;
use App\Repository\TchatRepository;
use Cocur\Slugify\Slugify;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        // $randGames = http://media.steampowered.com/steamcommunity/public/images/apps/{{ game.appid }}/{{ game.img_logo_url }}.jpg"

        $jsonGames = file_get_contents('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=97F647085699DE84DDB8E41A4A5F829A&steamid=76561198263045372&format=json&include_appinfo=1');

        $objGames = json_decode($jsonGames, true);

        $games = $objGames["response"]["games"];
        
        $jsonProfil = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198263045372');

        $objProfil = json_decode($jsonProfil, true);

        $profil = $objProfil["response"]["players"][0];        

        return $this->render('base/home.html.twig', [            
            'profil' => $profil,
        ]);
        
    }

    public function header($routeName, ChannelRepository $channelRepository)
    {      
    
        $jsonProfil = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=97F647085699DE84DDB8E41A4A5F829A&steamids=76561198263045372');

        $objProfil = json_decode($jsonProfil, true);

        $profil = $objProfil["response"]["players"][0];

        $channel = $channelRepository->findRecentChannel(5);

        return $this->render('base/_header.html.twig', [ 
            'route_name' => $routeName,
            'profil' => $profil,
            'channels' => $channel,
        ]);  
    }

    /**
     * @Route("/tchat/{slug}", name="tchat")
     */
    public function tchat(TchatRepository $tchatRepository, ChannelRepository $channelRepository, Request $request, string $slug)
    {
        $channel = $channelRepository->findOneBy([
            'slug' => $slug,
        ]);
        $tchats = $tchatRepository->findTchatByChannel($channel->getId());

        $tchat = new Tchat();
        $form = $this->createForm(TchatType::class, $tchat);
        $form->handleRequest($request);

        $array = ['Maxime', 'Mathias', 'Emmeline', 'Quentin'];
        $int = rand(0, 3);
        $user = $array[$int];

        if ($form->isSubmitted() && $form->isValid()) {
            $tchat->setUser($user);
            $tchat->setSentAt(new DateTime());
            $tchat->setChannel($channel);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tchat);
            $em->flush();

            return $this->redirectToRoute('tchat', ['slug' => $slug]);
        }

        return $this->render('base/tchat.html.twig', [
            'tchats' => $tchats,
            'form' => $form->createView(),
            'channel' => $channel->getName(),
        ]);
    }

    /**
     * @Route("/tchat/channel/create", name="create_channel")
     */
    public function createChannel(Request $request)
    {
        $slugify = new Slugify();
        $channel = new Channel();
        $form = $this->createForm(ChannelType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $slug = $slugify->slugify($name);
            $channel->setSlug($slug);

            $em = $this->getDoctrine()->getManager();
            $em->persist($channel);
            $em->flush();

            return $this->redirectToRoute('create_channel');
        }

        return $this->render('base/createChannel.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tchat/channel/liste", name="liste_channel")
     */
    public function listChannel(ChannelRepository $channelRepository)
    {
        $channel = $channelRepository->findAll();

        return $this->render('base/listeChannel.html.twig', [
            'channels' => $channel,
        ]);
    }

    /**
     * @Route("/tchat/channel/delete/{slug}", name="delete_channel")
     */
    public function DeleteChannel(string $slug, ChannelRepository $channelRepository)
    {
        $channel = $channelRepository->findOneBy([
            'slug' => $slug,
        ]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($channel);
        $em->flush();

        $this->addFlash('success', "Le salon a bien été supprimée");
        return $this->redirectToRoute('liste_channel');
    }
}

