<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Board", inversedBy="post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $board;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="child_post")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent_post;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="parent_post")
     */
    private $child_post;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->child_post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): self
    {
        $this->board = $board;

        return $this;
    }

    public function getParentPost(): ?self
    {
        return $this->parent_post;
    }

    public function setParentPost(?self $parent_post): self
    {
        $this->parent_post = $parent_post;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildPost(): Collection
    {
        return $this->child_post;
    }

    public function addChildPost(self $childPost): self
    {
        if (!$this->child_post->contains($childPost)) {
            $this->child_post[] = $childPost;
            $childPost->setParentPost($this);
        }

        return $this;
    }

    public function removeChildPost(self $childPost): self
    {
        if ($this->child_post->contains($childPost)) {
            $this->child_post->removeElement($childPost);
            // set the owning side to null (unless already changed)
            if ($childPost->getParentPost() === $this) {
                $childPost->setParentPost(null);
            }
        }

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

}
