<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;

/**
 * @author
 */
class CategoryBanner implements ImageInterface, ToggleableInterface
{
    use TimestampableTrait, ToggleableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    private $url;

    /**
     * @var Collection
     */
    private $showOnCategories;

    public function __construct()
    {
        $this->showOnCategories = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function setFile(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return ArrayCollection
     */
    public function getShowOnCategories()
    {
        return $this->showOnCategories;
    }

    /**
     * TaxonInterface $taxon
     */
    public function addCategory(TaxonInterface $taxon)
    {
        $this->showOnCategories->add($taxon);
    }

    /**
     * @param TaxonInterface $taxon
     */
    public function removeCategory(TaxonInterface $taxon)
    {
        $this->showOnCategories->remove($taxon);
    }
}
