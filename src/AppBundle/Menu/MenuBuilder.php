<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

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
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     * @param RepositoryInterface $taxonRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FactoryInterface $factory,
        RepositoryInterface $taxonRepository,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->factory = $factory;
        $this->taxonRepository = $taxonRepository;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return ItemInterface
     */
    public function createFullCategoriesMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $taxons = $this->getTaxons();
        $this->addAllChildren($menu, $taxons);

        $menu->addChild('Contact', ['route' => 'sylius_contact']);

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    public function createDropdownMenu()
    {
        $authenticated = $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav dropdown yamm-fw');

        $menu
            ->addChild('Cart', ['route' => 'sylius_cart_summary'])
            ->setExtra('icon', 'shopping-cart')
        ;

        $categoryMenu = $menu
            ->addChild('Categories', ['uri' => '#'])
            ->setAttribute('class', 'dropdown-toggle')
            ->setAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->setExtra('icon', 'tags')
        ;

        $taxons = $this->getTaxons();
        $this->addAllRootTaxonsAsChildren($categoryMenu, $taxons);

        $menu->addChild('Contact', ['route' => 'sylius_contact'])
            ->setExtra('icon', 'envelope')
        ;

        if ($authenticated) {
            $menu
                ->addChild('Account', ['route' => 'sylius_account_profile_show'])
                ->setExtra('icon', 'user')
            ;
            $menu
                ->addChild('Logout', ['route' => 'sylius_user_security_logout'])
                ->setExtra('icon', 'sign-out')
            ;
        }

        if (!$authenticated) {
            $menu
                ->addChild('Login', ['route' => 'sylius_user_security_login'])
                ->setExtra('icon', 'sign-in')
            ;
            $menu
                ->addChild('Register', ['route' => 'sylius_user_registration'])
                ->setExtra('icon', 'user-plus')
            ;
        }

        return $menu;
    }

    /**
     * @param array|null $categories
     *
     * @return ItemInterface
     */
    public function createPartialCategoriesMenu($categories = [])
    {
        Assert::notEmpty($categories, 'The "categories" array cannot be empty.');

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $taxons = $this->getTaxons();
        $this->addGivenChildren($menu, $taxons, $categories);

        return $menu;
    }

    /**
     * @return array
     */
    private function getTaxons()
    {
        return $this->taxonRepository->findRootNodes();
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface[] $taxons
     */
    private function addAllChildren(ItemInterface $menu, array $taxons)
    {
        foreach ($taxons as $taxon) {
            $this->addTaxonAsChild($menu, $taxon);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface[] $taxons
     * @param array $categories
     */
    private function addGivenChildren(ItemInterface $menu, array $taxons, array $categories)
    {
        foreach ($taxons as $taxon) {
            $taxonCode = $taxon->getCode();
            if (in_array($taxonCode, $categories, true)) {
                $this->addTaxonAsChild($menu, $taxon);
            }
        }
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface[] $rootTaxons
     */
    private function addAllRootTaxonsAsChildren(ItemInterface $menu, array $rootTaxons)
    {
        foreach ($rootTaxons as $taxon) {
             $menu->addChild($taxon->getName(), ['route' => $taxon]);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface $parentTaxon
     */
    private function addTaxonAsChild(ItemInterface $menu, TaxonInterface $parentTaxon)
    {
        $category = $menu->addChild($parentTaxon->getName(), ['route' => 'app_shop_product_index'])
            ->setAttribute('class', 'dropdown yamm-fw')
            ->setAttribute('style', 'display: block;')
            ->setLinkAttribute('class', 'dropdown-toggle')
            ->setLinkAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->addChild('', [])
        ;

        $this->appendCategoryDropdown($category, $parentTaxon);
    }

    /**
     * @param ItemInterface $category
     * @param TaxonInterface $parentTaxon
     */
    private function appendCategoryDropdown(ItemInterface $category, TaxonInterface $parentTaxon)
    {
        $taxons = $parentTaxon->getChildren();

        foreach ($taxons as $taxon) {
            $category->addChild($taxon->getName(), [
                'route' => 'app_shop_product_index',
                'routeParameters' => ['criteria' => [$parentTaxon->getCode() => [$taxon->getId()]]],
            ]);
        }
    }
}
