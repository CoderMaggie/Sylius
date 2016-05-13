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

use AppBundle\Entity\CategoryBannerInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CategoryBannerRepository extends EntityRepository
{
    /**
     * @param int $taxonId
     *
     * @return CategoryBannerInterface[]
     */
    public function findByTaxonId($taxonId)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return
            $queryBuilder
                ->innerJoin('o.showOnCategories', 'taxons')
                ->andWhere('taxons.id = :taxonId')
                ->setParameter('taxonId', $taxonId)
                ->getQuery()
                ->getResult()
        ;
    }
}
