<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Entity\User;
use App\Form\IdeaType;
use App\Repository\IdeaRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/", name="idea_index")
     * @return Response
     */
    public function index(IdeaRepository $ideaRepository, EntityManagerInterface $manager): Response
    {

        $ideas = $ideaRepository->findAll();
        $count = count($ideas);
        if ($this->getUser()) {
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
        }
        return $this->render('pages/ideas.html.twig', [
            'ideas' => $ideas,
            'count' => $count,
        ]);
    }

    /**
     * Permet de liker et disliker une idée
     * @Route ("/{id}/like", name="idea_like")
     * @param Idea $idea
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function like(Idea $idea, EntityManagerInterface $manager, Request $request): Response {

        if($this->getUser()){
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
            $idea = $manager->getRepository(Idea::class)->findOneBy(['id' => $request->get('id') ]);
            $user->addLike($idea);
            foreach ($user->getLikes() as $like){
                if($like === $idea){
                    $user->removeDislike($idea);
                    $manager->persist($user);
                }
            }
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('idea_index');
//            return $this->json([
//                'code' => 200,
//                'message' => 'Like bien ajouté',
//                'likes' => $ideaRepository->count()
//            ], 200);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     * @Route ("/{id}/dislike", name="idea_dislike")
     */
    public function dislike(EntityManagerInterface $manager, Request $request): Response
    {
        if($this->getUser()){
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getUsername()]);
            $idea = $manager->getRepository(Idea::class)->findOneBy(['id' => $request->get('id') ]);
            $user->addDislike($idea);
            foreach ($user->getDislike() as $dislike){
                if($dislike === $idea){
                    $user->removeLike($idea);
                    $manager->persist($user);
                }
            }
            $manager->persist($idea);
            $manager->flush();
            return $this->redirectToRoute('idea_index');
//            return $this->json([
//                'code' => 200,
//                'message' => 'Like bien supprime',
//                'likes' => $ideaRepository->count()
//            ], 200);
        }
        return $this->redirectToRoute('app_login');
    }


    /**
     * @Route("/index", name="my_ideas", methods={"GET"})
     */
    public function index2(EntityManagerInterface $manager): Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getEmail()]);
        $ideas = $manager->getRepository(Idea::class)->findBy(['author' => $user]);
        return $this->render('idea/index.html.twig', [
            'ideas' => $ideas,
        ]);
    }

    /**
     * @Route("/new", name="idea_new", methods={"GET","POST"})
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $idea = new Idea();
        $form = $this->createForm(IdeaType::class, $idea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($idea);
            $manager->flush();

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

            return $this->redirectToRoute('my_ideas', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('idea/edit.html.twig', [
            'idea' => $idea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="idea_delete", methods={"POST"})
     */
    public function delete(EntityManagerInterface $manager, Request $request, Idea $idea): Response
    {
        if ($this->isCsrfTokenValid('delete' . $idea->getId(), $request->request->get('_token'))) {
            $manager->remove($idea);
            $manager->flush();
        }

        return $this->redirectToRoute('my_ideas', [], Response::HTTP_SEE_OTHER);
    }
}
