<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Comment;
use App\Entity\Vote;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\CarRepository;
use App\Repository\VoteRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ProjectController extends AbstractController
{

    private $twig;
    private $entityMenager;
    private $commentRepository;
    private $voteRepository;
    private $pictureRepository;
    private $carRepository;
    private $user;

    public function __construct(Environment $twig, EntityManagerInterface $entityMenager, CommentRepository $commentRepository, PictureRepository $pictureRepository,VoteRepository $voteRepository, CarRepository $carRepository, Security $security)
    {
        $this->twig = $twig;
        $this->entityMenager = $entityMenager;
        $this->commentRepository = $commentRepository;
        $this->pictureRepository = $pictureRepository;
        $this->voteRepository = $voteRepository;
        $this->carRepository = $carRepository;
        $this->user = $security->getUser();
    }

    /**
     * @Route("/project/{slug}", name="project")
     */
    
    public function index(Request $request, Car $car): Response
    {
        $pictures = $this->pictureRepository->findBy(['car' => $car->getId()]);

        $comment = new Comment();
        $form = $this->createCommentForm($request, $comment);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreationDate(new \DateTime('now'));
            $comment->setCarPost($car);
            $comment->setAuthor($this->user);//loggedUser);
            
            $this->flush($comment);

            return $this->redirectToRoute('project', ['slug' => $car->getSlug()]);
        }
      
        $offset = max(0, $request->query->getInt('offset',0));
        $paginator = $this->commentRepository->getCommentPaginator($car,$offset);
          
        $up = $this->voteRepository->countVotes($car, 'up');       
        $down = $this->voteRepository->countVotes($car, 'down');
    
        return $this->renderPage($car,$paginator,$offset, $form, $pictures, $up, $down);
    }

    //zrobic metode post 
    /**
     * @Route("/project/{slug}/remove/{comment}", name="remove_comment")
     */
    public function removeComment(Comment $comment, string $slug)
    {
        $this->entityMenager->remove($comment);
        $this->entityMenager->flush();

        return $this->redirectToRoute('project', ['slug' => $slug]);
    }

    /**
     * @Route("/project/{slug}/edit/{comment}", name="edit_comment")
     */
    public function editCommentRequest(Request $request,  Car $car, Comment $comment): Response
    {
        $pictures = $this->pictureRepository->findBy(['car' => $car->getId()]);
        $up = $this->voteRepository->countVotes($car, 'up');       
        $down = $this->voteRepository->countVotes($car, 'down');

        $form = $this->createCommentForm($request, $car);

        $offset = max(0, $request->query->getInt('offset',0));
        $paginator = $this->commentRepository->getCommentPaginator($car,$offset);//$carRepository->findOneBy(['slug' => $slug]),$offset);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->flush($comment);

            return $this->redirectToRoute('project', ['slug' => $car->getSlug()]);
        }

        return $this->renderPage($car,$paginator,$offset, $form, $pictures, $up, $down);
    }

    /**
     * @Route("/project/{slug}/{vote}", name="vote", methods="POST")
     */
    public function vote(Car $car, $vote)
    {
        //$user = $this->get('security.token_storage')->getToken()->getUser();

        $voteR = new Vote();        
        $voteR->setUser($this->user);
        $voteR->setCar($car);

        if($vote == 'up' || $vote == 'down')
        {  
            $voteR->setVerdict($vote);
            $this->flush($voteR);
        }

        return $this->redirectToRoute('project', ['slug' => $slug]);
    }

    public function renderPage($car, $paginator, $offset, $form, $pictures, $up, $down) : Response
    {
        return new Response ($this->twig->render('main/project.html.twig', [
            'car' => $car,
            'author' => $this->get('security.token_storage')->getToken()->getUser(),
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
            'pictures' => $pictures,
            'votesUp' => array_pop($up[0]),
            'votesDown' => array_pop($down[0]) ,
            ]));
    }

    public function createCommentForm(Request $request, Comment $comment)  
    {
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        return $form;
    }

    public function handleCommentForm($form)
    {

    }

    public function flush($entity) : void
    {
        $this->entityMenager->persist($entity);
        $this->entityMenager->flush();
    }
    
}