<?php

namespace App\Controller\Backend\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_admin_home')]
    public function index(): Response
    {
        return $this->render('backend/home/index.html.twig', [
            'controller_name' => 'Admin Home',
        ]);
    }
}
