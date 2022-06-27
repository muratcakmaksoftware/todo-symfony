<?php

namespace App\Controller\Backend\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_admin_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [

        ]);
    }
}
