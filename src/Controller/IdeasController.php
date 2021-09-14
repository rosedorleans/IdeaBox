<?php

namespace App\Controller;

use App\Repository\IdeaRepository;
use App\Repository\MealRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ideas")
 */
class IdeasController extends AbstractController
{

    /**
     * @var IdeaRepository
     */
    private $repository;

    public function __construct(IdeaRepository $repository) {

        $this->repository = $repository;
    }
    /**
     * @Route("/", name="ideas_index")
     * @return Response
     */
    public function index(IdeaRepository $ideaRepository): Response {

        $ideas = $this->repository->findAll();
        $count = count($ideas);

        return $this->render('pages/ideas.html.twig', [
            'ideas' => $ideas,
            'count' => $count
        ]);
    }

}