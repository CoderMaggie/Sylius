<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RepositoryInterface
     */
    private $taxonRepository;

    /**
     * @param FactoryInterface $factory
     * @param RepositoryInterface $taxonRepository
     */
    public function __construct(
        FactoryInterface $factory,
        RepositoryInterface $taxonRepository
    ) {
        $this->factory = $factory;
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * @return ItemInterface
     */
    public function createCategoriesMenu()
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $taxons = $this->getCategories();

        foreach ($taxons as $taxon) {
            $this->addTaxonAsChild($menu, $taxon);
        }

        $menu->addChild('Contact', ['route' => 'sylius_contact']);

        return $menu;
    }

    /**
     * @return array
     */
    private function getCategories()
    {
        return $this->taxonRepository->findBy(['parent' => NULL]);
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface $parentTaxon
     */
    private function addTaxonAsChild($menu, $parentTaxon)
    {
        $category = $menu->addChild($parentTaxon->getName(), ['route' => $parentTaxon])
            ->setAttribute('class', 'dropdown yamm-fw')
            ->setAttribute('style', 'display: block;')
            ->setLinkAttribute('class', 'dropdown-toggle')
            ->setLinkAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->addChild('', []);


        $this->appendCategoryDropdown($category, $parentTaxon);
    }

    /**
     * @param ItemInterface $category
     * @param TaxonInterface $parentTaxon
     */
    private function appendCategoryDropdown($category, $parentTaxon)
    {
        $taxons = $parentTaxon->getChildren();

        foreach ($taxons as $taxon) {
            $category->addChild($taxon->getName(), ['route' => $taxon]);
        }
    }
}
