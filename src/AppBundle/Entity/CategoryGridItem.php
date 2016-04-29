<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;

class CategoryGridItem implements ResourceInterface, ToggleableInterface, ImageInterface
{
    use ToggleableTrait, TimestampableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $position;

    /**
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @var string
     */
    private $path;

    /**
     * @var TaxonInterface
     */
    private $category;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     *
     * @return bool
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     *
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo $file
     */
    public function setFile(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     *
     * @return bool
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return TaxonInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param TaxonInterface $category
     */
    public function setCategory(TaxonInterface $category)
    {
        $this->category = $category;
    }
}
