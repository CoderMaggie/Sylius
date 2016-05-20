<?php

namespace AppBundle\DataFixtures\Setup;

use AppBundle\Entity\CategoryBannerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadCategoryData extends DataFixture
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
        'App.Taxon.short_15-30_mins',
        'App.Taxon.basic_30-60_mins',
        'App.Taxon.long_1-2_hours',
        'App.Taxon.epic_3_hours',

    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $gameLength = $this->createTaxon($manager, 'Game Length');

        $this->createTaxon($manager, 'Quick (<15 mins)', $gameLength);
        $this->createTaxon($manager, 'Short (15-30 mins)', $gameLength);
        $this->createTaxon($manager, 'Basic (30-60 mins)', $gameLength);
        $this->createTaxon($manager, 'Long (1-2 hours)', $gameLength);
        $this->createTaxon($manager, 'Epic (3+ hours)', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Number of Players');

        $this->createTaxon($manager, 'Solitaire (1)', $gameLength);
        $this->createTaxon($manager, '2', $gameLength);
        $this->createTaxon($manager, '3', $gameLength);
        $this->createTaxon($manager, '4', $gameLength);
        $this->createTaxon($manager, '5', $gameLength);
        $this->createTaxon($manager, '6', $gameLength);
        $this->createTaxon($manager, 'Party Games (7+)', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Publisher');
        $this->createTaxon($manager, 'Mayfair', $gameLength);

        $gameLength = $this->createTaxon($manager, 'Game System');
        $this->createTaxon($manager, 'Catan', $gameLength);

        $this->createCategoryBanners($manager);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * @param ObjectManager $manager
     * @param string $name
     * @param TaxonInterface $parentTaxon
     * 
     * @return TaxonInterface
     */
    private function createTaxon(ObjectManager $manager, $name, TaxonInterface $parentTaxon = null)
    {
        $code = $this->getCode($name);

        /* @var TaxonInterface $taxon */
        $taxon = $this->get('sylius.factory.taxon')->createNew();
        $taxon->setCode($code);
        $taxon->setName($name);
        $taxon->setParent($parentTaxon);

        $manager->persist($taxon);
        $this->setReference('App.Taxon.'.$code, $taxon);

        return $taxon;
    }

    /**
     * @param ObjectManager $manager
     */
    private function createCategoryBanners(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');
        $categoryBannerFactory = $this->get('app.factory.category_banner');

        foreach ($finder->files()->in($this->pathToFixtureFolder . 'CategoryBanners/') as $img) {
            /** @var CategoryBannerInterface $categoryBanner */
            $categoryBanner = $categoryBannerFactory->createNew();

            $randomNumberOfPlayers = $this->faker->randomElement($this->numberOfPlayersTaxons);
            $randomGameLength = $this->faker->randomElement($this->gameLengthTaxons);

            $categoryBanner->addShowOnCategory($this->getReference($randomGameLength));
            $categoryBanner->addShowOnCategory($this->getReference($randomNumberOfPlayers));

            $categoryBanner->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
            $uploader->upload($categoryBanner);
            $manager->persist($categoryBanner);
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    private function getCode($name)
    {
        return preg_replace('/[^A-Za-z0-9\_ -]/', '', strtolower(str_replace(' ', '_', $name)));
    }
}
