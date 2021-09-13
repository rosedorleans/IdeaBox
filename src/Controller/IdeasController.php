<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ideas")
 */
class IdeasController extends AbstractController
{
    /**
     * @Route("/", name="ideas_index")
     * @return Response
     */
    public function index(): Response {
        return $this->render('pages/ideas.html.twig');
    }

}