<?php

namespace spec\AppBundle\Checker;

use AppBundle\Checker\ShippingMethodPromotionRuleChecker;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Promotion\Checker\RuleCheckerInterface;
use Sylius\Component\Promotion\Exception\UnsupportedTypeException;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;

/**
 * @mixin ShippingMethodPromotionRuleChecker
 *
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ShippingMethodPromotionRuleCheckerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Checker\ShippingMethodPromotionRuleChecker');
    }

    function it_implements_promotion_rule_checker_interface()
    {
        $this->shouldImplement(RuleCheckerInterface::class);
    }

    function it_has_configuration_form_types_name()
    {
        $this->getConfigurationFormType()->shouldReturn('app_promotion_rule_shipping_method_configuration');
    }

    function it_throws_unsupported_type_exception_if_subject_is_not_an_order(PromotionSubjectInterface $subject)
    {
        $this->shouldThrow(UnsupportedTypeException::class)->during('isEligible', [$subject, []]);
    }

    function it_returns_false_when_orders_last_shipment_is_empty(OrderInterface $order)
    {
        $order->getLastShipment()->willReturn(false);

        $this->isEligible($order, [])->shouldReturn(false);
    }

    function it_return_false_when_shipping_method_from_rule_is_different_than_the_one_from_order(
        ShipmentInterface $shipment,
        ShippingMethodInterface $orderShippingMethod,
        ShippingMethodInterface $ruleShippingMethod,
        OrderInterface $order
    ) {
        $shipment->getMethod()->willReturn($orderShippingMethod);
        $order->getLastShipment()->willReturn($shipment);

        $this->isEligible($order, ['shipping_method' => $ruleShippingMethod]);
    }

    function it_returns_true_when_shipping_method_from_rule_is_the_same_as_in_order(
        ShipmentInterface $shipment,
        ShippingMethodInterface $shippingMethod,
        OrderInterface $order
    ) {
        $shipment->getMethod()->willReturn($shippingMethod);
        $order->getLastShipment()->willReturn($shipment);

        $this->isEligible($order, ['shipping_method' => $shippingMethod])->shouldReturn(true);
    }
}
