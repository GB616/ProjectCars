<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class SearchController extends AbstractController
{

    private $twig;
    private $entityMenager;

    
    public function __construct(Environment $twig, EntityManagerInterface  $entityMenager)
    {
        $this->twig = $twig;
        $this->entityMenager = $entityMenager;
    }
    /**
     * @Route("/search", name="search")
     */
    public function index( Request $request): Response
    {
        $key = $request->get('key');
      
        if($key != null)
        { 
            return $this->searchInDB($key);
        }

        return $this->renderPage('', '');
    }

    public function renderPage($tabCar, $tabComment) : Response
    {
        return $this->render('main/search.html.twig', [
            'resultsCar' => $tabCar,
            'resultsComment' => $tabComment, 
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchInDB($key) 
    {
        $tab = [];
            $queryCar = $this->entityMenager->createQuery(
                "SELECT p.slug, p.year, p.model, p.description, p.engineDescription, p.internalDescription, p.externalDescription, u.name
                FROM App\Entity\Car p
                LEFT JOIN p.owner  u   
                WHERE 
                (p.model = :key )
                OR( p.description LIKE :key  )
                OR( p.engineDescription LIKE :key  )
                OR( p.internalDescription LIKE :key )
                OR( p.externalDescription LIKE :key )    
                ORDER BY p.model ASC"
            )->setParameter('key', '%'.$key.'%');

            $queryComment = $this->entityMenager->createQuery(
                "SELECT c.content, r.slug, r.year, r.model, u.name
                FROM App\Entity\Comment c
                LEFT JOIN c.carPost r
                LEFT JOIN c.author u
                WHERE (c.content LIKE :key)"
            )->setParameter('key', '%'.$key.'%');
       
            $tabCar = $queryCar->getResult();
            $tabComment = $queryComment->getResult();  
        
            return $this->renderPage($tabCar, $tabComment);
    }
}
