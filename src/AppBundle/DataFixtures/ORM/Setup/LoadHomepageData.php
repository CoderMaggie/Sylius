<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\CarouselItemInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Entity\ProductGridItem;

/**
 * Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class LoadHomepageData extends DataFixture
{
    protected $pathToFixtureFolder = __DIR__ . '/../../../Resources/fixtures/';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createCarouselItems($manager);
        $this->createProductGridItems($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function createCarouselItems(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        foreach ($finder->files()->in($this->pathToFixtureFolder . 'CarouselItems/') as $img) {
            /** @var CarouselItemInterface $item */
            $item = $this->get('app.factory.carousel_item')->createNew();
            $item->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));

            $uploader->upload($item);
            $manager->persist($item);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    private function createProductGridItems(ObjectManager $manager)
    {
        /** @var FactoryInterface $productGridItemFactory */
        $productGridItemFactory = $this->get('app.factory.product_grid_item');
        $this->createProductGridItemWithImage($productGridItemFactory, $manager);
        for ($i = 2; $i < 10; $i++) {
            /** @var ProductGridItem $productGridItem */
            $productGridItem = $productGridItemFactory->createNew();
            $productGridItem->setPosition($i);
            $productGridItem->setProduct($this->getReference('App.Product.' . $i));
            $manager->persist($productGridItem);
        }
    }

    /**
     * @param FactoryInterface $productGridItemFactory
     * @param ObjectManager $manager
     */
    private function createProductGridItemWithImage(FactoryInterface $productGridItemFactory, ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        foreach ($finder->files()->in($this->pathToFixtureFolder . 'ProductImages/') as $img) {
            /** @var ProductGridItem $productGridItem */
            $productGridItem = $productGridItemFactory->createNew();
            $productGridItem->setPosition(1);
            $productGridItem->setProduct($this->getReference('App.Product.1'));

            $productGridItem->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
            $uploader->upload($productGridItem);
            $manager->persist($productGridItem);

            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 60;
    }
}
