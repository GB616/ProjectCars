<?php

namespace App\Controller;


use App\Repository\CarRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    /**
     * @Route("/homepage/{sort?'new'}", name="homepage")
     */
    public function index(Environment $twig, Request $request, CarRepository $carRepository, string $sort, EntityManagerInterface $entityMenager, VoteRepository $voteRepository): Response
    {
        $offset = max(0, $request->query->getInt('offset',0));
        $paginator = $carRepository->getCarPaginator($offset, $sort);

        //to pooprawić bo niezbyt ładne
        foreach( $paginator as $row)
        {
            $up = $voteRepository->countVotes($row, 'up');
            $up = array_pop($up[0]);
            $row->setVoteUp($up);
            
            $down = $voteRepository->countVotes($row, 'down');
            $down = array_pop($down[0]);
            $row->setVoteDown($down);
        }
        //dd($_SERVER);
        return new Response ($this->twig->render('main/index.html.twig', [
            'cars' => $paginator,//$carRepository->findAll(),
            'previous' => $offset - CarRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CarRepository::PAGINATOR_PER_PAGE),
            'sort' => $sort, 
        ]));
    }
}
