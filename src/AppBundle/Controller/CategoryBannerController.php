<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\CategoryBannerInterface;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CategoryBannerController extends ResourceController
{
    /**
     * @param string $taxonCode
     *
     * @return CategoryBannerInterface
     */
    public function getCategoryBannerAction($taxonCode)
    {
        /** @var @RepositoryInterface $taxonRepository */
        $taxonRepository = $this->container->get('sylius.repository.taxon');

        /** @var @RepositoryInterface $bannerRepository */
        $bannerRepository = $this->container->get('app.repository.category_banner');

        $taxon = $taxonRepository->findOneBy(['code' => $this->getCode($taxonCode)]);

        $banners = $bannerRepository->findByTaxonId($taxon->getId());

        $banner = array_pop($banners);

        return $this->render('CategoryBanner/_banner.html.twig', ['banner' => $banner]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getCode($name)
    {
        return str_replace('-', '_', $name);
    }
}
