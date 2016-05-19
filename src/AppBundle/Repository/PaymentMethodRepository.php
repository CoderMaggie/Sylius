<?php

namespace AppBundle\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\PaymentMethodRepository as BasePaymentMethodRepository;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class PaymentMethodRepository extends BasePaymentMethodRepository
{
    /**
     * {@inheritdoc}
     */
    public function getQueryBuilderForChoiceType(array $options)
    {
        $queryBuilder = parent::getQueryBuilderForChoiceType($options);

        if ($options['chosenShippingMethod']) {
            $queryBuilder
                ->innerJoin('o.availableForShippingMethods', 'availableForShippingMethod')
                ->andWhere($queryBuilder->expr()->eq('availableForShippingMethod', ':shippingMethod'))
                ->setParameter('shippingMethod', $options['chosenShippingMethod'])
            ;
        }

        return $queryBuilder;
    }
}
