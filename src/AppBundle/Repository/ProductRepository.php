<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Repository;

use \Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ProductRepository extends BaseProductRepository
{
    /**
     * @param int $productId
     * @param array $parameters
     * @param int $limit
     *
     * @return ProductInterface[]
     */
    public function findSimilar($productId, array $parameters = [], $limit = null)
    {
        if (empty($parameters)) {
            throw new \InvalidArgumentException('The parameter array cannot be empty.');
        }

        /** @var ProductInterface $product */
        $product = $this->find($productId);
        $results = [];

        foreach ($parameters as $parameter) {
            if (null !== $parentTaxon = $this->getSpecificTaxon($product, $parameter)) {
                $results = $this->getByParentTaxon($parentTaxon, $productId, $limit);
                if (!empty($results)) {
                    return $results;
                }
            }
        }

        return $results;
    }

    /**
     * @param TaxonInterface $parentTaxon
     * @param int $productId
     * @param int $limit
     *
     * @return ProductInterface[]
     */
    private function getByParentTaxon(TaxonInterface $parentTaxon, $productId, $limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $orX = $queryBuilder->expr()->orX();

        foreach ($parentTaxon->getChildren() as $childTaxon) {
            $orX->add($queryBuilder->expr()->eq('taxon.id', $childTaxon->getId()));
        }

        return
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->add('where', $orX)
                ->andWhere(
                    $queryBuilder->expr()->not(
                        $queryBuilder->expr()->eq('o.id', $productId)
                ))
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        ;
    }

    /**
     * @param ProductInterface $product
     * @param string $taxonName
     *
     * @return TaxonInterface|null
     */
    private function getSpecificTaxon(ProductInterface $product, $taxonName)
    {
        foreach ($product->getTaxons() as $taxon) {
            if (!$taxon->isRoot()) {
                $taxon = $taxon->getParent();
            }
            if ($taxonName === $taxon->getName()) {
                return $taxon;
            }
        }

        return null;
    }
}
