<?php

namespace App\Controller\Backend\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(path: '/admin')]
class HomeController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_admin_home')]
    public function index(): Response
    {
        return $this->render('backend/home/index.html.twig', [
            'user' => $this->security->getUser()
        ]);
    }
}
