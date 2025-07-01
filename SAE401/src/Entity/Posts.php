<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    /**
     * @var Collection<int, Points>
     */
    #[ORM\OneToMany(targetEntity: Points::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $points;

    /**
     * @var Collection<int, PostsImages>
     */
    #[ORM\OneToMany(targetEntity: PostsImages::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $postsImages;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'post', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Activities $activity = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?Difficulties $difficulty = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gpxFileName = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $asc_ele = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $desc_ele = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_certified = null;

    public function __construct()
    {
        $this->points = new ArrayCollection();
        $this->postsImages = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Points>
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    public function addPoint(Points $point): static
    {
        if (!$this->points->contains($point)) {
            $this->points->add($point);
            $point->setPost($this);
        }

        return $this;
    }

    public function removePoint(Points $point): static
    {
        if ($this->points->removeElement($point)) {
            // set the owning side to null (unless already changed)
            if ($point->getPost() === $this) {
                $point->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PostsImages>
     */
    public function getPostsImages(): Collection
    {
        return $this->postsImages;
    }

    public function addPostsImage(PostsImages $postsImage): static
    {
        if (!$this->postsImages->contains($postsImage)) {
            $this->postsImages->add($postsImage);
            $postsImage->setPost($this);
        }

        return $this;
    }

    public function removePostsImage(PostsImages $postsImage): static
    {
        if ($this->postsImages->removeElement($postsImage)) {
            // set the owning side to null (unless already changed)
            if ($postsImage->getPost() === $this) {
                $postsImage->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getActivity(): ?Activities
    {
        return $this->activity;
    }

    public function setActivity(?Activities $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getDifficulty(): ?Difficulties
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulties $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getGpxFileName(): ?string
    {
        return $this->gpxFileName;
    }

    public function setGpxFileName(?string $gpxFileName): static
    {
        $this->gpxFileName = $gpxFileName;

        return $this;
    }

    public function getAscEle(): ?string
    {
        return $this->asc_ele;
    }

    public function setAscEle(?string $asc_ele): static
    {
        $this->asc_ele = $asc_ele;

        return $this;
    }

    public function getDescEle(): ?string
    {
        return $this->desc_ele;
    }

    public function setDescEle(?string $desc_ele): static
    {
        $this->desc_ele = $desc_ele;

        return $this;
    }

    public function isCertified(): ?bool
    {
        return $this->is_certified;
    }

    public function setIsCertified(?bool $is_certified): static
    {
        $this->is_certified = $is_certified;

        return $this;
    }
}
