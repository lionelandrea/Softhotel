<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\RegistrationFormType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        AdminRepository $adminRepository
    ): Response {
        $admin = new Admin();
        $form = $this->createForm(RegistrationFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $existingAdmin = $adminRepository->findOneBy([
                'email' => $admin->getEmail()
            ]);

            if ($existingAdmin) {
                $form->get('email')->addError(new FormError('Cet email existe déjà.'));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
    $admin->setPassword(
        $userPasswordHasher->hashPassword(
            $admin,
            $form->get('password')->getData()
        )
    );

    $admin->setRoles(['ROLE_USER']);

    $entityManager->persist($admin);
    $entityManager->flush();

    $this->addFlash('success', 'Compte créé avec succès.');

    return $this->redirectToRoute('app_login');
}

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}