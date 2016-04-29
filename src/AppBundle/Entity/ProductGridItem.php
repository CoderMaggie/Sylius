<?php

namespace AppBundle\Entity;

use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Resource\Model\ToggleableTrait;
use Sylius\Component\Core\Model\ProductInterface;

class ProductGridItem implements ResourceInterface, ToggleableInterface, ImageInterface
{
    use ToggleableTrait, TimestampableTrait;

    /**
     * @var int
     */
    private $id;

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
     * @var ProductInterface
     */
    private $product;

    /**
     * @var bool
     */
    private $showRibbonHot = false;

    /**
     * @var bool
     */
    private $showRibbonSale = false;

    /**
     * @var bool
     */
    private $showRibbonNew = false;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * @return bool
     */
    public function showRibbonHot()
    {
        return $this->showRibbonHot;
    }

    /**
     * @return bool
     */
    public function showRibbonSale()
    {
        return $this->showRibbonSale;
    }

    /**
     * @return bool
     */
    public function showRibbonNew()
    {
        return $this->showRibbonNew;
    }

    /**
     * @param bool $value
     */
    public function setShowRibbonHot($value)
    {
        $this->showRibbonHot = $value;
    }

    /**
     * @param bool $value
     */
    public function setShowRibbonSale($value)
    {
        $this->showRibbonSale = $value;
    }

    /**
     * @param bool $value
     */
    public function setShowRibbonNew($value)
    {
        $this->showRibbonNew = $value;
    }
}
