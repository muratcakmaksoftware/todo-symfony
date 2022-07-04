<?php

namespace App\Controller\Backend\Profile;

use App\Form\User\UserFormType;
use App\Repository\Backend\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/admin')]
class ProfileController extends AbstractController
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    #[Route('/profile', name: 'app_admin_profile', methods: ['GET', 'POST'])]
    public function index(Request $request, UserRepository $userRepository, UserInterface $user): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('password')) {
                $password = $form->get('password')->getData();
                if ($password != '' && strlen($password) >= 6 && strlen($password) <= 16) {
                    $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
                }
            }

            $userRepository->update($user, true);
            return $this->redirectToRoute('app_admin_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backend/profile/index.html.twig', [
            'form' => $form
        ]);
    }
}
