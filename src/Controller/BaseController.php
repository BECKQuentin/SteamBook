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
        return $this->render('base/home.html.twig', [
        ]);
    }

    public function header($routeName)
    {        
        return $this->render('base/_header.html.twig', [ 
            'route_name' => $routeName
        ]);  
    }
}
