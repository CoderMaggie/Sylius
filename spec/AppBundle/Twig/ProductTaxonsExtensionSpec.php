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
        $firstRoot->getCode()->willReturn('first-root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getCode()->willReturn('second-root');

        $firstTaxon->isRoot()->willReturn(false);
        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->isRoot()->willReturn(false);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $results = [
            'first-root' => [$firstTaxon, $secondTaxon],
            'second-root' => [$thirdTaxon],
        ];

        $this->getProductTaxonsExcluding($taxons)->shouldReturn($results);
    }

    function it_excludes_taxons_with_given_root_codes_and_gets_the_rest_as_an_array(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getCode()->willReturn('first-root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getCode()->willReturn('second-root');

        $firstTaxon->isRoot()->willReturn(false);
        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->isRoot()->willReturn(false);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxonsExcluding($taxons, ['first-root'])->shouldReturn(['second-root' => [$thirdTaxon]]);
    }

    function it_gets_taxons_matching_given_root_codes(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getCode()->willReturn('first-root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getCode()->willReturn('second-root');

        $firstTaxon->isRoot()->willReturn(false);
        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->isRoot()->willReturn(false);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxons($taxons, ['second-root'])->shouldReturn(['second-root' => [$thirdTaxon]]);
    }

    function it_returns_empty_array_when_taxons_with_given_root_codes_were_not_found(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getCode()->willReturn('first-root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getCode()->willReturn('second-root');

        $firstTaxon->isRoot()->willReturn(false);
        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->isRoot()->willReturn(false);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxons($taxons, ['third-root'])->shouldReturn([]);
    }

    function it_gets_all_taxons_when_no_root_codes_has_been_given(
        TaxonInterface $firstRoot,
        TaxonInterface $secondRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $firstRoot->isRoot()->willReturn(true);
        $firstRoot->getCode()->willReturn('first-root');

        $secondRoot->isRoot()->willReturn(true);
        $secondRoot->getCode()->willReturn('second-root');

        $firstTaxon->isRoot()->willReturn(false);
        $firstTaxon->getRoot()->willReturn($firstRoot);
        $secondTaxon->isRoot()->willReturn(false);
        $secondTaxon->getRoot()->willReturn($firstRoot);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($secondRoot);

        $taxons = [$firstTaxon, $secondTaxon, $thirdTaxon];

        $results = [
            'first-root' => [$firstTaxon, $secondTaxon],
            'second-root' => [$thirdTaxon],
        ];

        $this->getProductTaxons($taxons)->shouldReturn($results);
    }

    function it_ignores_all_root_taxons(
        TaxonInterface $singleRoot,
        TaxonInterface $firstTaxon,
        TaxonInterface $secondTaxon,
        TaxonInterface $thirdTaxon
    ) {
        $singleRoot->isRoot()->willReturn(true);
        $singleRoot->getCode()->willReturn('single-root');

        $firstTaxon->isRoot()->willReturn(true);
        $secondTaxon->isRoot()->willReturn(true);

        $thirdTaxon->isRoot()->willReturn(false);
        $thirdTaxon->getRoot()->willReturn($singleRoot);

        $taxons = [$singleRoot, $firstTaxon, $secondTaxon, $thirdTaxon];

        $this->getProductTaxons($taxons)->shouldReturn(['single-root' => [$thirdTaxon]]);
        $this->getProductTaxonsExcluding($taxons)->shouldReturn(['single-root' => [$thirdTaxon]]);
    }
}
