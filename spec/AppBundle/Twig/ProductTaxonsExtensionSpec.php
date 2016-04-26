<?php

namespace spec\AppBundle\Twig;

use AppBundle\Twig\ProductTaxonsExtension;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;

/**
 * @mixin ProductTaxonsExtension
 *
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ProductTaxonsExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Twig\ProductTaxonsExtension');
    }

    function it_extends_twig_extension()
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_has_a_name()
    {
        $this->getName()->shouldReturn('app_product_taxons');
    }

    function it_throws_exception_while_getting_taxons_from_an_empty_array()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('getProductTaxonsExcluding', [[]]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('getProductTaxons', [[]]);
    }

    function it_throws_exception_while_getting_taxons_from_an_array_of_type_other_than_taxon_interface()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->during('getProductTaxonsExcluding', [['what']]);
        $this->shouldThrow(\InvalidArgumentException::class)->during('getProductTaxons', [['what']]);
    }

    function it_gets_all_taxons_as_an_array_when_there_are_no_excludes(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getName()->willReturn('First root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getName()->willReturn('Second root');

        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $results = [
            'First root' => [$firstTaxon, $secondTaxon],
            'Second root' => [$thirdTaxon],
        ];

        $this->getProductTaxonsExcluding($taxons)->shouldReturn($results);
    }

    function it_excludes_root_taxons_with_given_names_and_gets_the_rest_as_an_array(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getName()->willReturn('First root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getName()->willReturn('Second root');

        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxonsExcluding($taxons, ['First root'])->shouldReturn(['Second root' => [$thirdTaxon]]);
    }

    function it_gets_all_taxons_with_stated_root_names(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getName()->willReturn('First root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getName()->willReturn('Second root');

        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxons($taxons, ['Second root'])->shouldReturn(['Second root' => [$thirdTaxon]]);
    }

    function it_gets_all_taxons_when_no_root_names_has_been_given(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getName()->willReturn('First root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getName()->willReturn('Second root');

        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $results = [
            'First root' => [$firstTaxon, $secondTaxon],
            'Second root' => [$thirdTaxon],
        ];

        $this->getProductTaxons($taxons)->shouldReturn($results);
    }
}
