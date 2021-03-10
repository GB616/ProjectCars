<?php
namespace App\EntityListener;

use App\Entity\Car;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CarEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Car $car, LifecycleEventArgs $event)
    {
        $car->computeSlug($this->slugger);
    }

    public function preUpdate(Car $car, LifecycleEventArgs $event)
    {
        $car->computeSlug($this->slugger);
    }
}