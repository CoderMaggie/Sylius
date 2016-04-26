<?php

namespace AppBundle\Twig;

use Sylius\Component\Core\Model\TaxonInterface;
use Webmozart\Assert\Assert;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ProductTaxonsExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('app_product_taxons_excluding', [$this, 'getProductTaxonsExcluding']),
            new \Twig_SimpleFilter('app_product_taxons', [$this, 'getProductTaxons']),
        ];
    }

    /**
     * @param TaxonInterface[] $taxons
     * @param array $excludes
     *
     * @return array
     */
    public function getProductTaxonsExcluding(array $taxons, array $excludes = [])
    {
        $taxonArray = $this->createTaxonArray($taxons);

        foreach ($excludes as $exclude) {
            if (array_key_exists($exclude, $taxonArray)) {
                unset($taxonArray[$exclude]);
            }
        }

        return $taxonArray;
    }

    /**
     * @param TaxonInterface[] $taxons
     * @param array $includes
     *
     * @return array
     */
    public function getProductTaxons(array $taxons, array $includes = [])
    {
        $taxonArray = $this->createTaxonArray($taxons);

        $results = [];

        foreach ($includes as $include) {
            if (array_key_exists($include, $taxonArray)) {
                $results[$include] = $taxonArray[$include];
            }
        }

        return empty($results) ? $taxonArray : $results;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_product_taxons';
    }

    /**
     * @param TaxonInterface[] $taxons
     *
     * @return array
     */
    private function createTaxonArray(array $taxons)
    {
        Assert::notEmpty($taxons, 'The "taxons" array cannot be empty.');
        Assert::allIsInstanceOf($taxons, TaxonInterface::class, sprintf('The "taxons" array doesn\'t contain only %s objects.', TaxonInterface::class));

        $tagsArray = [];

        foreach ($taxons as $taxon) {
            $rootName = $taxon->getRoot()->getName();

            if (!array_key_exists($rootName, $tagsArray)) {
                $tagsArray[$rootName] = [];
            }

            array_push($tagsArray[$rootName], $taxon);
        }

        return $tagsArray;
    }
}
