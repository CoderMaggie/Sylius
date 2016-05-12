<?php

namespace AppBundle\EventListener;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class MenuBuilderListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addBackendMenuItems(MenuBuilderEvent $event)
    {
        $menu = $event->getMenu();

        $homepageMenu = $menu->addChild('homepage')
            ->setLabel('app.menu.admin.main.homepage.header');

        $homepageMenu->addChild('carousel_items', ['route' => 'app_admin_carousel_item_index'])
            ->setLabel('app.menu.admin.main.homepage.carousel_items')
            ->setLabelAttribute('icon', 'file image outline');

        $homepageMenu->addChild('product_grid_items', ['route' => 'app_admin_product_grid_item_index'])
            ->setLabel('app.menu.admin.main.homepage.product_grid_items')
            ->setLabelAttribute('icon', 'grid layout');

        $homepageMenu->addChild('category_grid_items', ['route' => 'app_admin_category_grid_item_index'])
            ->setLabel('app.menu.admin.main.homepage.category_grid_items')
            ->setLabelAttribute('icon', 'square outline');

        $homepageMenu->addChild('category_banners', ['route' => 'app_admin_category_banner_index'])
            ->setLabel('app.menu.admin.main.homepage.category_banners')
            ->setLabelAttribute('icon', 'square');
    }
}
