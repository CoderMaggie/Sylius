<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadProductData extends DataFixture
{
    /**
     * @var int
     */
    private $productNumber = 1;

    protected $path = __DIR__ . '/../../../Resources/fixtures/';

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->createProductsWithImages($manager);

        $description = 'Your adventurous settlers seek to tame the remote but rich isle of Catan. Start by revealing Catan\'s many harbors and regions: pastures, fields, mountains, hills, forests, and desert. The random mix creates a different board virtually every game.';

        $this->createProduct('Game for 2 persons', $description, 1000, '12345678', $manager,
            ['App.Taxon.2']);
        $this->createProduct('Game for 3 persons', $description, 1000, '22345678', $manager,
            ['App.Taxon.3']);
        $this->createProduct('Quick Game', $description, 1000, '32345678', $manager,
            ['App.Taxon.Quick (<15 mins)']);
        $this->createProduct('Party Game', $description, 1000, '42345678', $manager,
            ['App.Taxon.Party Games (7+)']);
        $this->createProduct('Catan Game', $description, 1000, '52345678', $manager,
            ['App.Taxon.Catan']);
        $this->createProduct('Multicategorized Game', $description, 5000, '62345678', $manager,
            ['App.Taxon.Quick (<15 mins)', 'App.Taxon.2', 'App.Taxon.3', 'App.Taxon.Mayfair', 'App.Taxon.Catan']);
        $this->createProduct('Catan Crusaders', $description, 1000, '87935813', $manager,
            ['App.Taxon.Catan', 'App.Taxon.Mayfair']);
        $this->createProduct('Mayfair Adventurer', $description, 2000, '43517453', $manager,
            ['App.Taxon.Mayfair']);
        $this->createProduct('Mayfair Quick Game', $description, 1500, '80754812', $manager,
            ['App.Taxon.Quick (<15 mins)', 'App.Taxon.Mayfair']);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 50;
    }

    /**
     * @param string $name
     * @param string $description
     * @param int $price
     * @param string $sku
     * @param ObjectManager $manager
     * @param array $taxons
     *
     * @return ProductInterface
     */
    private function createProduct($name, $description, $price, $sku, ObjectManager $manager, array $taxons)
    {
        /** @var ProductInterface $product */
        $product = $this->get('sylius.factory.product')->createNew();

        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setSku($sku);

        $product->addChannel($this->getReference('App.Channel.WEB-UK'));

        foreach ($taxons as $taxon) {
            $product->addTaxon($this->getReference($taxon));
        }

        $this->setReference('App.Product.'.$this->productNumber, $product);
        $manager->persist($product);
        $this->productNumber++;
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadProductImages(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        foreach ($finder->files()->in($this->path . 'ProductImages/') as $img) {
            $image = $this->getProductVariantImageFactory()->createNew();
            $image->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));

            $uploader->upload($image);

            $manager->persist($image);

            $this->setReference('App.Image.'.$img->getBasename('.jpg'), $image);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function createProductsWithImages(ObjectManager $manager)
    {
        $this->loadProductImages($manager);

        $finder = new Finder();

        foreach ($finder->files()->in($this->path . 'ProductImages/') as $img) {
            /** @var ProductInterface $product */
            $product = $this->get('sylius.factory.product')->createNew();

            $product->setName($this->faker->word);
            $product->setDescription($this->faker->paragraph);
            $product->setPrice($this->faker->numberBetween(10, 300));
            $product->setSku($this->getUniqueSku());
            $product->addChannel($this->getReference('App.Channel.WEB-UK'));

            $randomTaxon = $this->faker->randomElement([
                'App.Taxon.Solitaire (1)',
                'App.Taxon.2',
                'App.Taxon.3',
                'App.Taxon.4',
                'App.Taxon.5',
                'App.Taxon.6',
                'App.Taxon.Party Games (7+)',
                'App.Taxon.Mayfair',
                'App.Taxon.Catan'
            ]);

            $product->addTaxon($this->getReference($randomTaxon));

            $variant = $product->getMasterVariant();

            $image = clone $this->getReference('App.Image.'.$img->getBasename('.jpg'));

            $variant->addImage($image);

            $this->setReference('App.Product.'.$this->productNumber, $product);

            $manager->persist($product);
            $this->productNumber++;
        }
    }

    /**
     * Get unique SKU.
     *
     * @param int $length
     *
     * @return string
     */
    protected function getUniqueSku($length = 5)
    {
        return $this->faker->unique()->randomNumber($length);
    }
}
