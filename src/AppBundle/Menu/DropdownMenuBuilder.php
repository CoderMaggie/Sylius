<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class DropdownMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     * @param TaxonRepositoryInterface $taxonRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FactoryInterface $factory,
        TaxonRepositoryInterface $taxonRepository,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->factory = $factory;
        $this->taxonRepository = $taxonRepository;
        $this->authorizationChecker = $authorizationChecker;
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
        } else {
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
     * @return array
     */
    private function getTaxons()
    {
        return $this->taxonRepository->findRootNodes();
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
}
