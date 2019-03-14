<?php

namespace App\Controller;

use App\Entity\PowerStrip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PowerStripController extends AbstractController {


    /**
     * @Route("/power_strip/{id}", name="app_get_ps")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getOne($id) {
        $ps = $this->getDoctrine()->getRepository(PowerStrip::class)->find($id);

        if ($ps->getUser() == $this->getUser()) {
            return $this->render('power_strip/index.html.twig', [
                'ps' => $ps,
            ]);
        }

        $this->redirectToRoute('home');
    }
}
