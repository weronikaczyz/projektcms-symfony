<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
* Class Photo.
*
* @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
* @ORM\Table(
*     name="photos",
*     uniqueConstraints={
*          @ORM\UniqueConstraint(
*              name="UQ_photos_1",
*              columns={"file"},
*          ),
*     },
* )
*
* @UniqueEntity(
*     fields={"file"}
* )
*/
class Photo
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
    * Created at.
    *
    * @var \DateTime
    *
    * @ORM\Column(type="datetime")
    *
    * @Gedmo\Timestampable(on="create")
    *
    * @Assert\DateTime
    */
    private $updatedAt;

    /**
    * File.
    *
    * @ORM\Column(
    *     type="string",
    *     length=191,
    *     nullable=false,
    *     unique=true,
    * )
    *
    * @Assert\NotBlank
    * @Assert\Image(
    *     maxSize = "1024k",
    *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg"},
    * )
    */
    private $file;

    /**
     * Page.
     *
     * @ORM\OneToOne(
     *      targetEntity="App\Entity\Page",
     *      inversedBy="photo"
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

    /**
    * Getter for Id.
    *
    * @return int|null Id
    */
    public function getId(): ?int
    {
        return $this->id;
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
    * @return mixed|null File
    */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
    * Setter for File name.
    *
    * @param string $file File
    */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
    * Getter for Page.
    *
    * @return \App\Entity\Page|null Page entity
    */
    public function getPage(): ?Page
    {
        return $this->page;
    }

    /**
    * Setter for Page.
    *
    * @param \App\Entity\Page $page Page entity
    */
    public function setPage(Page $page): void
    {
        $this->page = $page;
    }
}
