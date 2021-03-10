<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Picture;
use App\Form\CarFormType;
use App\Form\CarPictureType;
use App\Repository\CarRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use App\ImageOptimizer;

class ActionsCarController extends AbstractController
{
    private $twig;
    private $entityMenager;

    public function __construct(Environment $twig, EntityManagerInterface  $entityMenager)
    {
        $this->twig = $twig;
        $this->entityMenager = $entityMenager;
    }

    /**
     * @Route("/actionscar", name="actions_car")
     */
    public function index(Request $request,  ImageOptimizer $resizer): Response
    {
        $car = new Car();
        $carPicture = new Picture();
        $car->setOwner($this->get('security.token_storage')->getToken()->getUser());
        $car->setCreationDate(new \DateTime('now'));

        $form = $this->createForm(CarFormType::class, $car);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $this->flush($car);

           /* if(count($pictures) > 0 )
            {
                for($i =0; $i < count($pictures) ; $i++)
                {
                    $carPicture = new Picture();
                    $carPicture->setCar($car);
                    $carPicture->setPath($filename);
                    $this->entityMenager->persist($carPicture);
                    $this->entityMenager->flush();

                }
            }
            */    
            return $this->redirectToRoute('user', ['id' => $user->getId()]);
        }

       /* $photoDir = $_SERVER['DOCUMENT_ROOT'] . "/upload/photos/";

        $carPicture = new Picture();
        $pictures = [];
        $formCarPicture = $this->createForm(CarPictureType::class, $carPicture);
        $formCarPicture->handleRequest($request);
        
        if($formCarPicture->isSubmitted() )
        {
           // dd($car1);
           // $carPicture->setCar($car);
            if ($photo = $formCarPicture['path']->getData()) {
             
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                    return $this->redirectToRoute('homepage');
                }

               /// $carPicture->setPath($filename);
               
                //dd($formCarPicture['car']->getdata());
                
                //dd($carPicture);
                

               // $this->entityMenager->persist($carPicture);
               // $this->entityMenager->flush();

                $resizer->resize($filename, 600, 1066);
                //dd( $car->getSlug());
                //return $this->redirectToRoute('edit_car',['slug' => $car->getSlug()]);
                //return $this->redirectToRoute('homepage');
                $pictures[] = $filename;
            }
        }   */
        return new Response ($this->twig->render('main/actions_car.html.twig', [
            'user' => $this->get('security.token_storage')->getToken()->getUser(),
            'car_form' => $form->createView(),
            //'car_picture_form'=> $formCarPicture->createView(),
            'pictures' => '',
        ]));
    }

    /**
    * @Route("/actionscar/{slug}", name="edit_car")
    */
    public function edit(Request $request, PictureRepository $pictureRepository, ImageOptimizer $resizer, Car $car): Response
    {
        $carPicture = new Picture();
        $photoDir = $_SERVER['DOCUMENT_ROOT'] . "/upload/photos/";
        $pictures = $pictureRepository->findBy(['car' => $car->getId()]);
      
        $form = $this->createSomeForm($request,CarFormType::class, $car);
      
        if($form->isSubmitted() && $form->isValid())
        {
            $this->flush($car);

            return $this->redirectToRoute('project', ['slug' => $car->getSlug()]);
        }
      
        $formCarPicture = $this->createForm(CarPictureType::class, $carPicture);
        $formCarPicture->handleRequest($request);
        
        if($formCarPicture->isSubmitted() )
        {
            if ($photo = $formCarPicture['path']->getData()) {
             
                $filename = bin2hex(random_bytes(6)) . '.' . $photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                    return $this->redirectToRoute('homepage');
                }

                $carPicture->setCar($car);
                $carPicture->setPath($filename);
  
                $this->flush($carPicture);

                $resizer->resize($filename, 600, 1066);
               
                return $this->redirectToRoute('edit_car',['slug' => $car->getSlug()]);
            }
        }   

        return $this->renderPage($form, $formCarPicture, $pictures);
    }

    /**
    * @Route("/actionscar/delete/picture/{path}", name="delet_picture")
    */
    public function deletePicture(Picture $picture, string $path)
    {
        $this->entityMenager->remove($picture);
        $this->entityMenager->flush();

        return $this->redirectToRoute('project', ['slug' => $slug]);
    }

    public function renderPage($carForm, $carPictureForm,  $pictures) : Response
    {
        return new Response ($this->twig->render('main/actions_car.html.twig', [
            'car_form' => $carForm->createView(),
            'car_picture_form'=> $carPictureForm->createView(),
            'pictures' => $pictures,
        ]));
    }

    public function flush($entity) : void
    {
        $this->entityMenager->persist($entity);
        $this->entityMenager->flush();
    }

    public function createSomeForm(Request $request ,$formType, $entity)
    {
        $form = $this->createForm($formType, $entity);
        $form->handleRequest($request);
        return $form;
    }
}
