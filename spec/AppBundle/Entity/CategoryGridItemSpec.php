<?php

namespace spec\AppBundle\Entity;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

class CategoryGridItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\CategoryGridItem');
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

    function it_does_not_have_an_id_by_default()
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

    function its_title_is_mutable()
    {
        $this->setTitle('Title');
        $this->getTitle()->shouldReturn('Title');
    }

    function its_position_is_mutable()
    {
        $this->setPosition(5);
        $this->getPosition()->shouldReturn(5);
    }

    function it_does_not_have_a_file_by_default()
    {
        $this->hasFile()->shouldReturn(false);
        $this->getFile()->shouldReturn(null);
    }

    function it_does_not_have_a_path_by_default()
    {
        $this->hasPath()->shouldReturn(false);
        $this->getPath()->shouldReturn(null);
    }

    function its_file_is_mutable(\SplFileInfo $file)
    {
        $this->setFile($file);
        $this->getFile()->shouldReturn($file);
    }

    function its_path_is_mutable()
    {
        $this->setPath('path/to/file');
        $this->getPath()->shouldReturn('path/to/file');
    }

    function it_can_be_associated_with_a_taxon(TaxonInterface $category)
    {
        $this->setCategory($category);
        $this->getCategory()->shouldReturn($category);
    }
}
