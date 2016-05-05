<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\AttributeValueInterface;
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
        $shortDescription = 'Your adventurous settlers seek to tame the remote but rich isle of Catan.';

        $this->createProduct('Game for 2 persons', $shortDescription, $description, 1000, '12345678', $manager,
            ['App.Taxon.2']);
        $this->createProduct('Game for 3 persons', $shortDescription, $description, 1000, '22345678', $manager,
            ['App.Taxon.3']);
        $this->createProduct('Quick Game', $shortDescription, $description, 1000, '32345678', $manager,
            ['App.Taxon.quick_15_mins']);
        $this->createProduct('Party Game', $shortDescription, $description, 1000, '42345678', $manager,
            ['App.Taxon.party_games_7']);
        $this->createProduct('Catan Game', $shortDescription, $description, 1000, '52345678', $manager,
            ['App.Taxon.catan']);
        $this->createProduct('Multicategorized Game', $shortDescription, $description, 5000, '62345678', $manager,
            ['App.Taxon.quick_15_mins', 'App.Taxon.2', 'App.Taxon.3', 'App.Taxon.mayfair', 'App.Taxon.catan']);
        $this->createProduct('Catan Crusaders', $shortDescription, $description, 1000, '87935813', $manager,
            ['App.Taxon.catan', 'App.Taxon.mayfair']);
        $this->createProduct('Mayfair Adventurer', $shortDescription, $description, 2000, '43517453', $manager,
            ['App.Taxon.mayfair']);
        $this->createProduct('Mayfair Quick Game', $shortDescription, $description, 1500, '80754812', $manager,
            ['App.Taxon.quick_15_mins', 'App.Taxon.mayfair']);

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
     * @param string $shortDescription
     * @param string $description
     * @param int $price
     * @param string $sku
     * @param ObjectManager $manager
     * @param array $taxons
     *
     * @return ProductInterface
     */
    private function createProduct($name, $shortDescription, $description, $price, $sku, ObjectManager $manager, array $taxons)
    {
        /** @var ProductInterface $product */
        $product = $this->get('sylius.factory.product')->createNew();

        $product->setName($name);
        $product->setShortDescription($shortDescription);
        $product->setDescription($description);
        $product->setPrice($price);
        $product->setSku($sku);
        $product->addChannel($this->getReference('App.Channel.WEB-UK'));

        foreach ($taxons as $taxon) {
            $product->addTaxon($this->getReference($taxon));
        }

        /** @var ProductVariantInterface $variant */
        $variant = $product->getMasterVariant();
        $variant->setDepth($this->faker->numberBetween(0, 100));
        $variant->setHeight($this->faker->numberBetween(0, 100));
        $variant->setWidth($this->faker->numberBetween(0, 100));
        $variant->setWeight($this->faker->randomFloat(2,0,5));

        $product->addAttribute($this->getAttribute('contents'));

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
                'App.Taxon.solitaire_1',
                'App.Taxon.2',
                'App.Taxon.3',
                'App.Taxon.4',
                'App.Taxon.5',
                'App.Taxon.6',
                'App.Taxon.party_games_7',
                'App.Taxon.mayfair',
                'App.Taxon.catan'
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
     * @param string $code
     *
     * @return AttributeValueInterface
     */
    protected function getAttribute($code)
    {
        /* @var AttributeValueInterface $attributeValue */
        $attributeValue = $this->get('sylius.factory.product_attribute_value')->createNew();
        $attributeValue->setAttribute($this->getReference('App.Attribute.'.$code));
        $attributeValue->setValue($this->faker->sentence(20));

        return $attributeValue;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    protected function getUniqueSku($length = 5)
    {
        return $this->faker->unique()->randomNumber($length);
    }
}
