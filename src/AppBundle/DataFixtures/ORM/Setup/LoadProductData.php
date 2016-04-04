<?php

namespace AppBundle\DataFixtures\Setup;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\DataFixtures\DataFixture;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Payment\Model\PaymentMethodInterface;

class LoadProductData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var ProductInterface $product */
        $product = $this->get('sylius.factory.product')->createNew();
        $product->setName('Mayfair Games Catan Board Game');
        $product->setDescription('Your adventurous settlers seek to tame the remote but rich isle of Catan. Start by revealing Catan\'s many harbors and regions: pastures, fields, mountains, hills, forests, and desert. The random mix creates a different board virtually every game.');
        $product->setPrice(10000);
        $product->setSku('123456789');
        $product->addChannel($this->getReference('App.Channel.WEB-UK'));
        $product->addTaxon($this->getReference('App.Taxon.2'));
        $product->addTaxon($this->getReference('App.Taxon.3'));
        $product->addTaxon($this->getReference('App.Taxon.4'));
        $product->addTaxon($this->getReference('App.Taxon.5'));
        $product->addTaxon($this->getReference('App.Taxon.Mayfair'));
        $product->addTaxon($this->getReference('App.Taxon.Catan'));
        $product->addTaxon($this->getReference('App.Taxon.Basic (30-60 mins)'));
        $product->setMainTaxon($this->getReference('App.Taxon.Mayfair'));

        $manager->persist($product);
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 50;
    }
}
