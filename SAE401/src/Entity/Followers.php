<?php

namespace App\Entity;

use App\Repository\FollowersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowersRepository::class)]
class Followers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'followers')]
    private ?User $follower_id = null;

    #[ORM\ManyToOne(inversedBy: 'followers')]
    private ?User $followed_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollowerId(): ?User
    {
        return $this->follower_id;
    }

    public function setFollowerId(?User $follower_id): static
    {
        $this->follower_id = $follower_id;

        return $this;
    }

    public function getFollowedId(): ?User
    {
        return $this->followed_id;
    }

    public function setFollowedId(?User $followed_id): static
    {
        $this->followed_id = $followed_id;

        return $this;
    }
}
