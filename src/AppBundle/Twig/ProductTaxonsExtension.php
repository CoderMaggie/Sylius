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
            if (isset($taxonArray[$exclude])) {
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

        if (empty($includes)) {
            return $taxonArray;
        }

        $results = [];

        foreach ($includes as $include) {
            if (isset($taxonArray[$include])) {
                $results[$include] = $taxonArray[$include];
            }
        }

        return empty($results) ? [] : $results;
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

        $taxonArray = [];

        foreach ($taxons as $taxon) {
            if ($taxon->isRoot()) {
                continue;
            }

            $rootName = $taxon->getRoot()->getName();

            if (!isset($taxonArray[$rootName])) {
                $taxonArray[$rootName] = [];
            }

            $taxonArray[$rootName][] = $taxon;
        }

        return $taxonArray;
    }
}
