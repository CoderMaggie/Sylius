<?php

namespace spec\AppBundle\Entity;

use AppBundle\Entity\CategoryBanner;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;
use Sylius\Component\Taxonomy\Model\Taxon;

/**
 * @mixin CategoryBanner
 */
class CategoryBannerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Entity\CategoryBanner');
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

    function it_does_not_have_file_by_default()
    {
        $this->hasFile()->shouldReturn(false);
        $this->getFile()->shouldReturn(null);
    }

    function it_does_not_have_path_by_default()
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

    function it_has_url()
    {
        $this->getUrl()->shouldReturn(null);

        $this->setUrl('http://test.com');
        $this->getUrl()->shouldReturn('http://test.com');
    }

    function it_initializes_categories_collection_by_default()
    {
        $this->getShowOnCategories()->shouldHaveType(Collection::class);
    }

    function it_adds_new_show_on_category(TaxonInterface $taxon)
    {
        $this->addShowOnCategory($taxon);
        $this->getShowOnCategories()->contains($taxon)->shouldReturn(true);
    }

    function it_removes_show_on_category(TaxonInterface $taxon)
    {
        $this->removeShowOnCategory($taxon);
        $this->getShowOnCategories()->contains($taxon)->shouldReturn(false);
    }
}
