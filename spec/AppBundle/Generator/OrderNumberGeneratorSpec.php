<?php

namespace spec\AppBundle\Generator;

use AppBundle\Generator\OrderNumberGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Sequence\Model\SequenceInterface;
use Sylius\Component\Sequence\Number\AbstractGenerator;
use Sylius\Component\Sequence\Number\GeneratorInterface;

/**
 * @mixin OrderNumberGenerator
 */
class OrderNumberGeneratorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('GG', 1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Generator\OrderNumberGenerator');
    }

    function it_extends_abstract_generator()
    {
        $this->shouldHaveType(AbstractGenerator::class);
    }

    function it_implements_generator_interface()
    {
        $this->shouldImplement(GeneratorInterface::class);
    }

    function it_does_nothing_when_order_already_has_a_number(
        OrderInterface $order,
        SequenceInterface $sequence
    ) {
        $order->getNumber()->willReturn('GG1');

        $order->setNumber(Argument::any())->shouldNotBeCalled();
        $sequence->getIndex()->shouldNotBeCalled();
        $sequence->incrementIndex()->shouldNotBeCalled();

        $this->generate($order, $sequence);
    }

    function it_generates_order_number_using_sequences_index(
        OrderInterface $order,
        SequenceInterface $sequence
    ) {
        $order->getNumber()->willReturn(null);

        $sequence->getIndex()->willReturn(0);
        $order->setNumber('GG1')->shouldBeCalled();
        $sequence->incrementIndex()->shouldBeCalled();

        $this->generate($order, $sequence);
    }
}
