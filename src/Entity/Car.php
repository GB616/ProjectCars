<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $torque;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $engineDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $externalDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $internalDescription;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Drivetrain::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $drivetrain;

    /**
     * @ORM\ManyToOne(targetEntity=Fuel::class, inversedBy="cars")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fuel;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="car")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="car")
     */
    private $pictures;

    /**
     * @ORM\Column(type="date")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="float")
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="carPost")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $voteUp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $voteDown;

    /**
     * @ORM\OneToMany(targetEntity=Vote::class, mappedBy="car")
     */
    private $votes;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->brand . ' ' . $this->model . ' ' . $this->getOwner() ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getTorque(): ?int
    {
        return $this->torque;
    }

    public function setTorque(?int $torque): self
    {
        $this->torque = $torque;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEngineDescription(): ?string
    {
        return $this->engineDescription;
    }

    public function setEngineDescription(?string $engineDescription): self
    {
        $this->engineDescription = $engineDescription;

        return $this;
    }

    public function getExternalDescription(): ?string
    {
        return $this->externalDescription;
    }

    public function setExternalDescription(?string $externalDescription): self
    {
        $this->externalDescription = $externalDescription;

        return $this;
    }

    public function getInternalDescription(): ?string
    {
        return $this->internalDescription;
    }

    public function setInternalDescription(?string $internalDescription): self
    {
        $this->internalDescription = $internalDescription;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getDrivetrain(): ?drivetrain
    {
        return $this->drivetrain;
    }

    public function setDrivetrain(?drivetrain $drivetrain): self
    {
        $this->drivetrain = $drivetrain;

        return $this;
    }

    public function getFuel(): ?fuel
    {
        return $this->fuel;
    }

    public function setFuel(?fuel $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setCar($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getCar() === $this) {
                $picture->setCar(null);
            }
        }

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    
    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
    
   /**
    *@ORM\PrePersist
    */
 
    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(float $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCarPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCarPost() === $this) {
                $comment->setCarPost(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function computeSlug(SluggerInterface $slugger)
    {
        if(!$this->slug || '-' === $this->slug)
        {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }

    }

    public function getVoteUp(): ?int
    {
        return $this->voteUp;
    }

    public function setVoteUp(?int $voteUp): self
    {
        $this->voteUp = $voteUp;

        return $this;
    }

    public function getVoteDown(): ?int
    {
        return $this->voteDown;
    }

    public function setVoteDown(?int $voteDown): self
    {
        $this->voteDown = $voteDown;

        return $this;
    }

    /**
     * @return Collection|Vote[]
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
            $vote->setCar($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getCar() === $this) {
                $vote->setCar(null);
            }
        }

        return $this;
    }
}
