<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\CarouselItemInterface;
use AppBundle\Entity\CategoryGridItem;
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

    protected $numberOfPlayersTaxons = [
        'App.Taxon.solitaire_1',
        'App.Taxon.2',
        'App.Taxon.3',
        'App.Taxon.4',
        'App.Taxon.5',
        'App.Taxon.6',
        'App.Taxon.party_games_7',
        'App.Taxon.mayfair',
        'App.Taxon.catan'
    ];

    protected $gameLengthTaxons = [
        'App.Taxon.quick_15_mins',
        'App.Taxon.short_15_30_mins',
        'App.Taxon.basic_30_60_mins',
        'App.Taxon.long_1_2_hours',
        'App.Taxon.epic_3_hours',

    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createCarouselItems($manager);
        $this->createProductGridItems($manager);
        $this->createCategoryGridItems($manager);

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
     * @param ObjectManager $manager
     */
    private function createCategoryGridItems(ObjectManager $manager)
    {
        /** @var FactoryInterface $categoryGridItemFactory */
        $categoryGridItemFactory = $this->get('app.factory.category_grid_item');
        $this->createCategoryGridItemWithImage($categoryGridItemFactory, $manager);
        for ($i = 2; $i < 8; $i++) {
            /** @var CategoryGridItem $categoryGridItem */
            $categoryGridItem = $categoryGridItemFactory->createNew();
            $categoryGridItem->setPosition($i);

            $randomNumberOfPlayers = $this->faker->randomElement($this->numberOfPlayersTaxons);
            $randomGameLength = $this->faker->randomElement($this->gameLengthTaxons);

            $categoryGridItem->setCategory($this->getReference($randomNumberOfPlayers));
            $categoryGridItem->setCategory($this->getReference($randomGameLength));

            $manager->persist($categoryGridItem);
        }
    }

    /**
     * @param FactoryInterface $categoryGridItemFactory
     * @param ObjectManager $manager
     */
    private function createCategoryGridItemWithImage(FactoryInterface $categoryGridItemFactory, ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        foreach ($finder->files()->in($this->pathToFixtureFolder . 'CategoryImages/') as $img) {
            /** @var CategoryGridItem $categoryGridItem */
            $categoryGridItem = $categoryGridItemFactory->createNew();

            $randomNumberOfPlayers = $this->faker->randomElement($this->numberOfPlayersTaxons);
            $randomGameLength = $this->faker->randomElement($this->gameLengthTaxons);

            $categoryGridItem->setCategory($this->getReference($randomNumberOfPlayers));
            $categoryGridItem->setCategory($this->getReference($randomGameLength));

            $categoryGridItem->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
            $uploader->upload($categoryGridItem);
            $manager->persist($categoryGridItem);

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
