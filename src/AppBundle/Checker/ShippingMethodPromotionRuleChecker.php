<?php

namespace AppBundle\Checker;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Promotion\Checker\RuleCheckerInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ShippingMethodPromotionRuleChecker implements RuleCheckerInterface
{
    const TYPE = 'shipping_method';

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedTypeException
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration)
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnsupportedTypeException($subject, OrderInterface::class);
        }
        if (!$subject->getLastShipment()) {
            return false;
        }

        return $subject->getLastShipment()->getMethod()->getId() === $configuration['shipping_method']->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormType()
    {
        return 'app_promotion_rule_shipping_method_configuration';
    }
}
