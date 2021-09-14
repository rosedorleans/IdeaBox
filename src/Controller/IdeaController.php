<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Form\IdeaType;
use App\Repository\IdeaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/idea")
 */
class IdeaController extends AbstractController
{

    /**
     * @var IdeaRepository
     */
    private $repository;

    public function __construct(IdeaRepository $repository) {

        $this->repository = $repository;
    }
    /**
     * @Route("/", name="idea_index")
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

    /**
     * @Route("/index", name="idea_index2", methods={"GET"})
     */
    public function index2(IdeaRepository $ideaRepository): Response
    {
        return $this->render('idea/index.html.twig', [
            'ideas' => $ideaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="idea_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $idea = new Idea();
        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($idea);
            $entityManager->flush();

            return $this->redirectToRoute('idea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('idea/new.html.twig', [
            'idea' => $idea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="idea_show", methods={"GET"})
     */
    public function show(Idea $idea): Response
    {
        return $this->render('idea/show.html.twig', [
            'idea' => $idea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="idea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Idea $idea): Response
    {
        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('idea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('idea/edit.html.twig', [
            'idea' => $idea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="idea_delete", methods={"POST"})
     */
    public function delete(Request $request, Idea $idea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$idea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($idea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('idea_index', [], Response::HTTP_SEE_OTHER);
    }
}
