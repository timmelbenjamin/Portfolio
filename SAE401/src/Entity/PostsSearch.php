<?php

namespace App\Entity;

class PostsSearch
{
    private ?string $location = null;  

    private ?Activities $activities = null;

    public function getLocation(): ?string  
    {
        return $this->location;
    }

    public function setLocation(?string $location): self  
    {
        $this->location = $location;
        return $this;
    }

    public function getActivities(): ?Activities  
    {
        return $this->activities;
    }

    public function setActivities(?Activities $activities): self  
    {
        $this->activities = $activities;
        return $this;
    }
}