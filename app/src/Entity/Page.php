<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="pages")
 */
class Page
{
    /**
    * Use constants to define configuration options that rarely change instead
    * of specifying them in app/config/config.yml.
    * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
    *
    * @constant int NUMBER_OF_ITEMS
    */
    const NUMBER_OF_ITEMS = 10;

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
     * Author user.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="pages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
    * Created at.
    *
    * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
    * Updated at.
    *
    * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
    * File.
    *
    * @ORM\Column(
    *     type="string",
    *     length=192,
    *     nullable=true,
    *     unique=true,
    * )
    *
    * @Assert\Image(
    *     maxSize = "1024k",
    *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg"},
    * )
    */
    private $file;

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
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
    public function setContent(string $content): void
    {
        $this->content = $content;
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
    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    /**
    * Getter of author.
    *
    * @return App\Entity\User|null author
    */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter of author.
     *
     * @param App\Entity\User $author Author user.
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
    * Getter for Created at.
    *
    * @return \DateTimeInterface|null Created at
    */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
    * Setter for Created at.
    *
    * @param \DateTimeInterface $createdAt Created at
    */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
    * Getter for Updated at.
    *
    * @return \DateTimeInterface|null Updated at
    */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
    * Setter for Updated at.
    *
    * @param \DateTimeInterface $updatedAt Updated at
    */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
    * Getter for File.
    *
    * @return string|null|UploadedFile File
    */
    public function getFile()
    {
        return $this->file;
    }

    /**
    * Setter for File.
    *
    * @param string $file File
    */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}
