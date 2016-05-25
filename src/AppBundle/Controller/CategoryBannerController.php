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
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class CategoryBannerController extends ResourceController
{
    /**
     * @param Request $request
     *
     * @return CategoryBannerInterface
     */
    public function getCategoryBannerAction(Request $request)
    {
        $criteria = $request->query->get('criteria', []);

        /** @var RepositoryInterface $bannerRepository */
        $bannerRepository = $this->container->get('app.repository.category_banner');

        $taxonsIds = [];
        foreach ($criteria as $taxons) {
            $taxonsIds = array_merge($taxonsIds, $taxons);
        }

        $banner = $this->getBanner($bannerRepository, $taxonsIds);

        return $this->render('CategoryBanner/_banner.html.twig', ['banner' => $banner]);
    }

    /**
     * @param $bannerRepository
     * @param $taxonsIds
     *
     * @return CategoryBannerInterface
     */
    private function getBanner($bannerRepository, $taxonsIds)
    {
        $banners = $bannerRepository->findByTaxonsIds($taxonsIds);

        if (empty($banners)) {
            return null;
        }

        return $banners[array_rand($banners)];
    }
}
