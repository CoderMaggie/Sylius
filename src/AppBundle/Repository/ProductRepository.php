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
     * @return QueryBuilder
     */
    public function createListQueryBuilder(array $criteria = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->addSelect('taxon')
            ->leftJoin('o.taxons', 'taxon')
        ;

        return $queryBuilder;
    }

    /**
     * @param int $id
     *
     * @return ProductInterface|null
     */
    public function findWithTaxons($id)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return
            $queryBuilder
                ->leftJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->eq('o.id', $id))
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }

    /**
     * @param int $productId
     * @param array $parameters
     * @param int|null $limit
     *
     * @return ProductInterface[]
     *
     * @throws \InvalidArgumentException
     */
    public function findSimilar($productId, array $parameters = [], $limit = null)
    {
        if (empty($parameters)) {
            throw new \InvalidArgumentException('The parameter array cannot be empty.');
        }

        $product = $this->findWithTaxons($productId);
        if (null === $product) {
            throw new \InvalidArgumentException(sprintf('Product with id "%s" has not been found.', $productId));
        }

        $results = [];

        foreach ($parameters as $parameter) {
            $rootTaxon = $this->getTaxonWithRoot($product, $parameter);

            if (null !== $rootTaxon) {
                $results = $this->getByTaxon($rootTaxon, $productId, $limit);
                if (!empty($results)) {
                    return $results;
                }
            }
        }

        return $results;
    }

    /**
     * @param TaxonInterface $taxon
     * @param int $productId
     * @param int|null $limit
     *
     * @return ProductInterface[]
     */
    private function getByTaxon(TaxonInterface $taxon, $productId, $limit = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $taxonId = $taxon->getId();

        return
            $queryBuilder
                ->innerJoin('o.taxons', 'taxon')
                ->andWhere($queryBuilder->expr()->eq('taxon.id', $taxonId))
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
     * @param string $rootName
     *
     * @return TaxonInterface|null
     *
     * @throws \InvalidArgumentException
     */
    private function getTaxonWithRoot(ProductInterface $product, $rootName)
    {
        foreach ($product->getTaxons() as $taxon) {
            if ($taxon->isRoot()) {
                throw new \InvalidArgumentException('Product should not be assigned to the root taxon.');
            }

            $rootTaxon = $taxon->getRoot();
            if ($rootName === $rootTaxon->getName()) {
                return $taxon;
            }
        }

        return null;
    }
}
