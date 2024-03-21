<?php

namespace App\Controller;

use App\Entity\Ubicacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PruebaController extends AbstractController
{
    #[Route('/prueba', name: 'app_prueba')]
    public function index(): Response
    {
        return $this->render('prueba/index.html.twig', [
            'controller_name' => 'PruebaController',
        ]);
    }

    #[Route('/api/ubicacion', name: 'app_ubi', methods: ['POST'])]
    public function postUbi(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ubicacion = new Ubicacion();

        $ubicacion->setUbCoordenadas([$request->request->get('latitud'), $request->request->get('longitud')]);
        $entityManager->persist($ubicacion);
        $entityManager->flush();
        return $this->json($ubicacion);
    }
}
