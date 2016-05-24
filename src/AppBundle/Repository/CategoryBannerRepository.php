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
     * @param array $taxonsIds
     *
     * @return CategoryBannerInterface[]
     */
    public function findByTaxonsIds($taxonsIds)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return
            $queryBuilder
                ->innerJoin('o.showOnCategories', 'taxons')
                ->andWhere($queryBuilder->expr()->in('taxons.id', ':taxonsIds'))
                ->setParameter('taxonsIds', $taxonsIds)
                ->getQuery()
                ->getResult()
            ;
    }
}
