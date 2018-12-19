<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenRepository")
 * @ORM\Table(name="tokens", uniqueConstraints={@ORM\UniqueConstraint(name="token_value_uidx", columns={"token_value"})})
 * @ORM\HasLifecycleCallbacks
 */
class Token
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $token_value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Artist", mappedBy="token", cascade={"persist", "remove"})
     */
    private $artist;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Album", mappedBy="token", cascade={"persist", "remove"})
     */
    private $album;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenValue(): ?string
    {
        return $this->token_value;
    }

    public function setTokenValue(string $token_value): self
    {
        $this->token_value = $token_value;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(Artist $artist): self
    {
        $this->artist = $artist;

        // set the owning side of the relation if necessary
        if ($this !== $artist->getToken()) {
            $artist->setToken($this);
        }

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): self
    {
        $this->album = $album;

        // set the owning side of the relation if necessary
        if ($this !== $album->getToken()) {
            $album->setToken($this);
        }

        return $this;
    }

    /**
     * Automatically inserts the datetime on creation
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedAt(new \DateTime("now"));

    }

}
