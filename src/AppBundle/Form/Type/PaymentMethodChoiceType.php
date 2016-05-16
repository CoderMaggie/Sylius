<?php

namespace AppBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\Payment\PaymentMethodChoiceType as BasePaymentMethodChoiceType;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class PaymentMethodChoiceType extends BasePaymentMethodChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $queryBuilder = function (Options $options) {
            $repositoryOptions = [
                'disabled' => $options['disabled'],
                'channel' => $options['channel'],
                'chosenShippingMethod' => $options['chosenShippingMethod'],
            ];

            return function (PaymentMethodRepositoryInterface $repository) use ($repositoryOptions) {
                return $repository->getQueryBuilderForChoiceType($repositoryOptions);
            };
        };

        $resolver
            ->setDefault('chosenShippingMethod', ShippingMethodInterface::class)
            ->setDefault('query_builder', $queryBuilder)
        ;
    }
}
