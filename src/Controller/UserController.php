<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;

class UserController extends AbstractController {

    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile() {

        $form_change_password = $this->createForm(ChangePasswordType::class, $this->getUser(), [
            'action' => $this->generateUrl('app_change_password'),
            'method' => 'POST',
        ]);



        $form_change_email = $this->createForm(UserType::class, $this->getUser());

        return $this->render('user/profile.html.twig', [
            'form_change_password' => $form_change_password->createView(),
            'form_change_email' => $form_change_email->createView()
        ]);
    }

}
