<?php

namespace App\Controller;

use App\Entity\AccessLog;
use App\Entity\PowerStrip;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index() {
        $powerStrips = $this->getDoctrine()->getRepository(PowerStrip::class)->findBy(['user' => $this->getUser()]);

        $logs = [];// $this->getDoctrine()->getRepository(AccessLog::class)->findBy(['user' => $this->getUser()]);

        return $this->render('home/index.html.twig', [
            'powerstrips' => $powerStrips,
            'logs' => $logs
        ]);
    }

    /**
     * @Route("/doc", name="doc")
     */
    public function documentation() {
        return $this->render('home/doc.html.twig', []);
    }



}
