<?php

namespace spec\AppBundle\Entity;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderItemInterface;

class OrderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\Order');
    }

    function it_extends_sylius_core_order()
    {
        $this->shouldHaveType(Order::class);
    }

    function it_returns_0_subtotal_when_there_are_no_items()
    {
        $this->getSubtotal()->shouldReturn(0);
    }

    function it_returns_subtotal_of_all_items_as_its_subtotal(
        OrderItemInterface $orderItem1,
        OrderItemInterface $orderItem2
    ) {
        $orderItem1->getTotal()->willReturn(80);
        $orderItem1->getSubtotal()->willReturn(100);
        $orderItem2->getTotal()->willReturn(400);
        $orderItem2->getSubtotal()->willReturn(500);

        $orderItem1->setOrder($this)->shouldBeCalled();
        $orderItem2->setOrder($this)->shouldBeCalled();
        $this->addItem($orderItem1);
        $this->addItem($orderItem2);

        $this->getSubtotal()->shouldReturn(600);
    }
}
