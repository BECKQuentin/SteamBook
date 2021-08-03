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
        return $this->render('base/home.html.twig', [
        ]);
    }

    public function header($routeName, ChannelRepository $channelRepository)
    {        
        $channel = $channelRepository->findAll();

        return $this->render('base/_header.html.twig', [ 
            'route_name' => $routeName,
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
     * @Route("/tchat/create/channel", name="create_channel")
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
}
