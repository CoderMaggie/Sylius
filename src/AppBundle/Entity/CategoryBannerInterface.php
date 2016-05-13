<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
interface CategoryBannerInterface extends ResourceInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     */
    public function setUrl($url);

    /**
     * @return ArrayCollection
     */
    public function getShowOnCategories();

    /**
     * TaxonInterface $taxon
     */
    public function addShowOnCategory(TaxonInterface $taxon);

    /**
     * @param TaxonInterface $taxon
     */
    public function removeShowOnCategory(TaxonInterface $taxon);
}
