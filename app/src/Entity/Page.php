<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="pages")
 */
class Page
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * Content.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=4500)
     */
    private $content;

    /**
     * Published
     *
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * Get ID
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter of title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter of title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter of content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter of Content.
     *
     * @param string $content Content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Getter of published.
     *
     * @return boolean|null published
     */
    public function getPublished(): ?bool
    {
        return $this->published;
    }

    /**
     * Setter of published.
     *
     * @param boolean $published Published.
     */
    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
}
