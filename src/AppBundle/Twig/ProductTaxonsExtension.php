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
        $taxonArray = $this->createNonRootTaxonArray($taxons);

        if (empty($excludes)) {
            return $taxonArray;
        }

        $rootTaxons = $this->createRootTaxonArray($taxons);
        $excludes = $this->convertRootCodesToNames($rootTaxons, $excludes);

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
        $taxonArray = $this->createNonRootTaxonArray($taxons);

        if (empty($includes)) {
            return [];
        }

        $results = [];
        $rootTaxons = $this->createRootTaxonArray($taxons);
        $includes = $this->convertRootCodesToNames($rootTaxons, $includes);

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
    private function createNonRootTaxonArray(array $taxons)
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

    /**
     * @param TaxonInterface[] $taxons
     *
     * @return array
     */
    public function createRootTaxonArray(array $taxons)
    {
        Assert::notEmpty($taxons, 'The "taxons" array cannot be empty.');
        Assert::allIsInstanceOf($taxons, TaxonInterface::class, sprintf('The "taxons" array doesn\'t contain only %s objects.', TaxonInterface::class));

        $rootArray = [];

        foreach ($taxons as $taxon) {
            $root = $taxon->getRoot();
            if (null !== $root) {
                $rootArray[$root->getCode()] = $root->getName();
            }
        }

        return $rootArray;
    }

    /**
     * @param array $roots
     * @param array $parameters
     *
     * @return array
     */
    public function convertRootCodesToNames(array $roots, array $parameters)
    {
        $newParameters = [];

        foreach ($parameters as $parameter) {
            if (isset($roots[$parameter])) {
                $newParameters[] = $roots[$parameter];
            }
        }

        return $newParameters;
    }
}
