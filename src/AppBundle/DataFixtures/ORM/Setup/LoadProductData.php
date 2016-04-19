<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ProductInterface;

class LoadProductData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
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
            ['App.Taxon.Catan']);
        $this->createProduct('Mayfair Adventurer', $description, 2000, '43517453', $manager,
            ['App.Taxon.Mayfair']);

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
     * @param Objectmanager $manager
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

        $manager->persist($product);
    }
}
