<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Entity\IdeaLike;
use App\Entity\User;
use App\Form\IdeaType;
use App\Repository\IdeaLikeRepository;
use App\Repository\IdeaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
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

//    /**
//     * Permet de liker et disliker une idée
//     * @Route ("/{id}/like", name="idea_like")
//     * @param Idea $idea
//     * @param IdeaLikeRepository $likeRepository
//     * @return Response
//     */
//    public function like(Idea $idea, ObjectManager $manager, IdeaLikeRepository $likeRepository): Response {
//
//        $user = $this->getUser();
//
////      Si l'utilisateur n'est pas connecté (user=null)
////      Retourner un message d'erreur
//        if (!$user) return $this->json([
//            'code' => 403,
//            'message' => 'Unauthorized'
//        ], 403);
//
////      Si l'utilisateur a liké l'idée
//        if ($idea->isLikedByUser($user)) {
//            $like = $likeRepository->findOneBy([
//                'idea' => $idea,
//                'user' => $user
//            ]);
//
////          Enlever le like
//            $manager->remove($like);
//            $manager->flush();
//
////          Retourner un message de succes
//            return $this->json([
//                'code' => 200,
//                'message' => 'Like bien supprime',
//                'likes' => $likeRepository->count(['idea' => $idea])
//            ], 200);
//        }
//
////      Ajouter un like
//        $like = new IdeaLike();
//        $like->setIdea($idea)
//             ->getUser($user);
//
//        $manager->persist($like);
//        $manager->flush();
//
////      Retourner un message de succes
//        return $this->json([
//            'code' => 200,
//            'message' => 'Like bien ajoute',
//            'likes' => $likeRepository->count(['idea' => $idea])
//        ], 200);
//
//    }


    /**
     * @Route("/index", name="my_ideas", methods={"GET"})
     */
    public function index2(IdeaRepository $ideaRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getEmail()]);
        $ideas = $entityManager->getRepository(Idea::class)->findBy(['author' => $user]);
        return $this->render('idea/index.html.twig', [
            'ideas' => $ideas,
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
