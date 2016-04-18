<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\CarouselItemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CarouselItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\CarouselItem');
    }

    function it_implements_carousel_item_interface()
    {
        $this->shouldImplement(CarouselItemInterface::class);
    }

    function it_is_a_Sylius_resource()
    {
        $this->shouldImplement(ResourceInterface::class);
    }

    function it_is_togglable()
    {
        $this->shouldImplement(ToggleableInterface::class);
    }

    function it_is_has_an_image()
    {
        $this->shouldImplement(ImageInterface::class);
    }

    function it_does_not_have_id_by_default()
    {
        $this->getId()->shouldReturn(null);
    }

    function it_is_enabled_by_default()
    {
        $this->shouldBeEnabled();
    }

    function it_can_be_disabled()
    {
        $this->disable();
        $this->shouldNotBeEnabled();
    }
    
    function it_has_tooltip()
    {
        $this->getTooltip()->shouldReturn(null);
        
        $this->setTooltip('Test Tooltip');
        
        $this->getTooltip()->shouldReturn('Test Tooltip');
    }
    
    function it_has_url()
    {
        $this->getUrl()->shouldReturn(null);
        
        $this->setUrl('http://test.com');
        $this->getUrl()->shouldReturn('http://test.com');
    }

    function it_has_position()
    {
        $this->getPosition()->shouldReturn(null);

        $this->setPosition(1);
        $this->getPosition()->shouldReturn(1);
    }
}
