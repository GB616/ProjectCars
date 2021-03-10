<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Car;
use App\Entity\Picture;
use App\Repository\CarRepository;
use App\Form\UserPictureType;
use App\Form\CarPictureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use App\ImageOptimizer;

class UserController extends AbstractController
{

    public function __construct(Environment $twig, EntityManagerInterface $entityMenager)
    {
        $this->twig = $twig;
        $this->entityMenager = $entityMenager;
    }
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(User $user, Request $request,  CarRepository $carRepository, ImageOptimizer $resizer, string $id): Response
    {

        $photoDir = $_SERVER['DOCUMENT_ROOT'] . "/upload/photos/";  
        $user1 = $this->get('security.token_storage')->getToken()->getUser();
       
        $logged = false;
        if($user1->getId() == $id)
            $logged = true;
        
        $form = $this->createForm(UserPictureType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            
            if ($photo = $form['profilePicturePath']->getData()) {
                
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                    return $this->redirectToRoute('homepage');
                }
                
                $user->setProfilePicturePath($filename);

                $this->entityMenager->persist($user);
                $this->entityMenager->flush();

                $resizer->resize($filename, 250 , 150);

            return $this->redirectToRoute('user', ['id' => $user->getId()]);
            }            
        }

        return new Response ($this->twig->render('main/user.html.twig', [
            'cars' =>  $carRepository->findBy(['owner' => $user]),
            'user_picture' => $form->createView(),
            'logged' => $logged,
        ]));
    }

    
    /**
     * @Route("/user/{id}/{slug}", name="remove_car")
     */
    public function removeCar(string $id,  $slug, CarRepository $carRepository)
    {
        //dd($slug);
        $car = new Car();
        $car = $carRepository->findOneBy(['slug' => $slug]);
        //dd($car);
        $this->entityMenager->remove($car);
        $this->entityMenager->flush();

        return $this->redirectToRoute('user', ['id' => $id]);
    }
}
